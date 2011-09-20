<?php

class ProductosController extends Zend_Controller_Action
{
    public function init()
    {
    $translator = new Zend_Translate(
        array(
        'adapter' => 'array',
        'content' => APPLICATION_PATH.'/../resources/languages',
        'locale'  => 'es',
        'scan' => Zend_Translate::LOCALE_DIRECTORY
        )
    );
    Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

    public function indexAction()
    {
        // instanciar el modelo (tabla de productos)
        $productos = new Application_Model_DbTable_Productos();

        // generar el select
        $select = $productos->select()
                            ->from( $productos )
                            ->order( 'descripcion ASC', 'nombre ASC' );

        // asignar resultado a una variable de la vista
        $this->view->productos = $productos->fetchAll( $select );
    }

    public function editAction()
    {
        // Agregar forma y ponerle un botón de guardar.
        $forma = new Application_Form_Productos();
        $forma->enviar->setLabel( 'Guardar' );
        
        $this->view->forma = $forma;

        if ( $this->getRequest()->isPost() ) {
            $datos = $this->getRequest()->getPost();

            if ( $forma->isValid( $datos ) ) {
                // asignar los valores de la forma a variables
                $id = (int) $forma->getValue( 'id' );
                $nombre = $forma->getValue( 'nombre' );
                $descripcion = $forma->getValue( 'descripcion' );
                $precio = $forma->getValue( 'precio' );
                $existencia = $forma->getValue( 'existencia' );
                $imagen =$forma->imagen->getValue ( 'imagen' );    
                $mime =$forma->imagen->getMimeType ( 'imagen' );
                
                // actualizar los datos
                $productos = new Application_Model_DbTable_Productos();
                $productos->updateProducto( $id, $nombre, $descripcion, $precio, $existencia, $imagen, $mime );

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
                $imagen = $forma->imagen->getvalue ( 'imagen' );
                $mime = $forma->imagen->getMimeType ( 'imagen' );
                
                // actualizar los datos
                $productos = new Application_Model_DbTable_Productos();
                $productos->addProducto( $nombre, $descripcion, $precio, $existencia, $imagen, $mime );

                // redirigir al index
                $this->_helper->redirector( 'index' );
            } else {
                $forma->populate( $datos );
            }
        }
    }
}
