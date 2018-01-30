<?php


namespace AppBundle\Controller;


use AppBundle\Repository\AppRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormInterface;

abstract class AppController extends FOSRestController
{

    /**
     * @return AppRepository
     */
    protected abstract function getRepository();

    /**
     * @param $id
     * @param bool $strict
     * @return object
     */
    protected function loadModel($id, $strict = true)
    {
        $model = $this->getRepository()->find($id);
        if ($strict && is_null($model)) {
            throw $this->createNotFoundException();
        }
        return $model;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function getErrorsFromForm($form)
    {
        // TODO use serializer
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

}