<?php
namespace AppBundle\Command;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class XmlCommand extends ContainerAwareCommand
{
    const TASK_NAME = 'xml:generate';
    const XML_PATH = 'api/stream';

    protected function configure()
    {
        $this
            ->setName(XmlCommand::TASK_NAME)
            ->setDescription('Generate xml from user data')
            ->addArgument(
                'email',
                InputArgument::OPTIONAL,
                'Email'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Проверим запущена ли команда уже, если да то выходим
        $lockHandler = new LockHandler('xml.generate.lock');
        if (!$lockHandler->lock()) {
            $output->writeln('Command is locked');
            return 0;
        }

        $email = $input->getArgument('email');

        if($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('The argument must be valid email');
        }

        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $persons = $em->getRepository('AppBundle:Person')->findAll();

        $serializer = SerializerBuilder::create()->build();

        //ищем XML для экспорта, если нет - создаем
        $fs = new Filesystem();
        $path = $container->get('kernel')->getRootDir().'/data/result.xml';
        if($fs->exists($path)) {
            $content = file_get_contents($path);
            $xml = new \SimpleXMLElement($content);
        } else {
            $xml = new \SimpleXMLElement('<persons/>');
            $xml->asXML($path);
        }

        //идем по всем Person и ищем соответствующий айди в XML
        foreach($persons as $person) {
            $serialized = $serializer->serialize($person, 'xml');

            $t = $xml->xpath(sprintf('//person[@id="%d"]', $person->getId()));

            if($t) {
                //если находим - удаляем нод
                $dom = dom_import_simplexml($t[0]);
                $dom->parentNode->removeChild($dom);
            }
            //вставляем новый нод
            $target = $xml->xpath('/persons');
            $dom = dom_import_simplexml($target[0]);
            $insertDom = $dom->ownerDocument->importNode(dom_import_simplexml(new \SimpleXMLElement($serialized)), true);
            $dom->appendChild($insertDom);

            $xml->asXML($path);

        }

        $publicPath = $container->get('kernel')->getRootDir().'/../web/'.XmlCommand::XML_PATH;

        $fs->copy($path, $publicPath);

        $timeFinished = new \DateTime();

        if($email) {
            $context = $container->get('router')->getContext();

            $message = \Swift_Message::newInstance()
                ->setSubject('Task finished')
                ->setFrom('noreply@example.com')
                ->setTo($email)
                ->setBody(
                    $container->get('templating')->render(
                        'emails/xmlFinished.html.twig',
                        [
                            'taskName' => XmlCommand::TASK_NAME,
                            'time' => $timeFinished,
                            'link' => $context->getHost().'/'.XmlCommand::XML_PATH
                        ]
                    ),
                    'text/html'
                )
            ;
            $container->get('mailer')->send($message);
        }



        return 0;
    }
}