<?php

namespace Catalog\Controller;

use Catalog\Model\ModificationInterface;
use Catalog\Service\BaseServiceInterface;
use Catalog\Service\ProductServiceInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\View\Model\FeedModel;

class ProductsController extends AbstractActionController
{

    /**
     * @var ProductServiceInterface
     */
    protected $productService = null;

    /**
     * @var BaseServiceInterface
     */
    protected $baseService = null;

    public function __construct(
        ProductServiceInterface $productService,
        BaseServiceInterface $baseService
    )
    {
        $this->productService = $productService;
        $this->baseService = $baseService;
    }

    public function indexAction()
    {

        //Debug::dump($this->productService->getFullInArray(116));die();
        /*return new ViewModel([
            'products' => $this->productService->fetchAll(),
            'productParams' => $this->productService->fetchAllProductParams(),
            'productModificationsTable' => $this->productService->fetchAllProductModificationParamValues()
        ]);*/

        return new JsonModel(
            $this->productService->fetchAllProductParams()
            //$this->productService->fetchAllProductModificationParamValues()
            //$this->productService->getFullInArray(116)
        );
    }

    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        /*if($id){
            Debug::dump($this->productService->getFullInArray($id)); die();
            //Debug::dump($this->productService->find($id));
            //Debug::dump($this->productService->fetchParamsByProduct($id));
        }*/

        /*$modifications = $this->productService->fetchModificationsByProduct($id);
        //Debug::dump($this->modificationTableValues($modifications));

        return new ViewModel([
            'product' => $this->productService->find($id),
            'productParams' => $this->productService->fetchParamsByProduct($id),
            'modifications' => $this->productService->fetchModificationsByProduct($id),
            'modificationsProperty' => $this->productService->fetchModificationPropertyByProduct($id),
            'modificationsTable' => $this->modificationTableValues($modifications)
        ]);*/
        return new JsonModel(
            //$this->productService->getFullArrayProductsByCategory($id)
            $this->baseService->getProductById($id)
        );
    }

    /**
     * @param $modifications array | HydratingResultSet
     * @return array
     */
    public function modificationTableValues($modifications)
    {
        $modificationsTableValues = array();
        $modificationsArray = $modifications->toArray();
        if(!empty($modificationsArray))
            foreach ($modificationsArray as $modification){
                $values = array();
                $values[] = $modification["sku"];
                $modificationPropertyValues = $this->productService->fetchModificationPropertyValues($modification['id']);
                foreach ($modificationPropertyValues->toArray() as $modificationPropertyValue){
                    $values[] = $modificationPropertyValue['value'];
                }

                $modificationsTableValues[] = $values;
            }


        return $modificationsTableValues;
    }


}

