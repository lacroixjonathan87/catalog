<?php

namespace AppBundle\Command;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:populate')
            ->setDescription('Populate database with data')
            ->setHelp('Populate database with data from json file')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Opening json file');
        $path = $input->getArgument('path');
        $content = file_get_contents($path);
        $data = json_decode($content, true);

        /** @var Registry $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $now = new \DateTime();

        $categories = array();

        $output->write('Populate products');
        $products = $data['products'];
        foreach ($products as $elt){

            $categoryName = $elt['category'];
            if(!array_key_exists($categoryName, $categories)){
                $category = new Category();
                $category->setName($categoryName);
                $category->setCreatedAt($now);
                $category->setModifiedAt($now);
                $em->persist($category);

                $categories[$categoryName] = $category;
            }

            $category = $categories[$categoryName];

            $product = new Product();
            $product->setName($elt['name']);
            $product->setCategory($category);
            $product->setSku($elt['sku']);
            $product->setPrice($elt['price']);
            $product->setQuantity($elt['quantity']);
            $product->setCreatedAt($now);
            $product->setModifiedAt($now);
            $em->persist($product);

            $em->flush();

            $output->write('.');
        }
        $output->writeln('');

    }

}