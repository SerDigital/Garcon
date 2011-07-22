<?php

class ProductosController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$productos = new Application_Model_DbTable_Productos();
    	$this->view->productos = $productos->fetchAll( null, 'nombre ASC' );
    }

    public function editAction()
    {
    	// Agregar forma y ponerle un botón de guardar.
    	$forma = new Application_Form_Productos();
    	$forma->enviar->setLabel( 'Guardar' );
    	$this->view->forma = $forma;

    	if ( $this->getRequest()->isPost() ) {
    		$datos = $this->getRequest()->getPost();

    		if ( $forma->isValid($datos) ) {
    			// asignar los valores de la forma a variables
    			$id = (int) $forma->getValue( 'id' );
    			$nombre = $forma->getValue( 'nombre' );
    			$descripcion = $forma->getValue( 'descripcion' );
    			$precio = $forma->getValue( 'precio' );
    			$existencia = $forma->getValue( 'existencia' );

    			// actualizar los datos
    			$productos = new Application_Model_DbTable_Productos();
    			$productos->updateProducto( $id, $nombre, $descripcion, $precio, $existencia );

    			// redirigir al index
    			$this->_helper->redirector( 'index' );
            } else {
            	$forma->populate( $datos );
            }
        } else {
        	$id = $this->_getParam( 'id', 0 );
        	if ( $id > 0 ) {
        		$productos = new Application_Model_DbTable_Productos();
        		$forma->populate( $productos->getProducto( $id ) );
            }
        }
    }

    public function deleteAction()
    {
    	// checar si hay un post
    	if ( $this->getRequest()->isPost() ) {
    		// verificar si el post proviene de un botón de borrado; llamado borrar.
    		$borrar = $this->getRequest()->getPost('borrar');

    		// si el botón es afirmativo, borra el producto
    		if ( $borrar == 'Sí' ) {
    			// obtener id (cast)
    			$id = (int) $this->getRequest()->getPost( 'id' );

    			// obtener el modelo de la tabla productos
    			$productos = new Application_Model_DbTable_Productos();

    			// borrar el registro con la id determinada
    			$productos->deleteProducto( $id );
            }

            // redirigir al listado
            $this->_helper->redirector( 'index' );
        } else {
            // obtener el parámetro de id
            $id = (int) $this->_getParam( 'id', 0 );
            
            // obtener una instancia del modelo de la tabla productos
            $productos = new Application_Model_DbTable_Productos();

            // Asignar a la variable productos, los datos del producto con la id determinada
            $this->view->producto = $productos->getProducto( $id );
        }
    }

    public function addAction()
    {
    	// Agregar forma y ponerle un botón de guardar.
    	$forma = new Application_Form_Productos();
    	$forma->enviar->setLabel( 'Agregar' );
    	$this->view->forma = $forma;

    	if ( $this->getRequest()->isPost() ) {
    		$datos = $this->getRequest()->getPost();

    		if ( $forma->isValid($datos) ) {
    			// asignar los valores de la forma a variables
    			$nombre = $forma->getValue( 'nombre' );
    			$descripcion = $forma->getValue( 'descripcion' );
    			$precio = $forma->getValue( 'precio' );
    			$existencia = $forma->getValue( 'existencia' );

    			// actualizar los datos
    			$productos = new Application_Model_DbTable_Productos();
    			$productos->addProducto( $nombre, $descripcion, $precio, $existencia );

    			// redirigir al index
    			$this->_helper->redirector( 'index' );
            } else {
            	$forma->populate( $datos );
            }
        }
    }
}
