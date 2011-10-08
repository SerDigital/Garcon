<?php

class Application_Form_Productos extends Zend_Form
{
    public function init()
    {
    	$this->setName( 'producto' );
    	$this->setAttrib( Zend_Form::ENCTYPE_MULTIPART, true );

    	$id = new Zend_Form_Element_Hidden( 'id' );
    	$id->addFilter( 'Int' );

    	$nombre = new Zend_Form_Element_Text( 'nombre' );
    	$nombre->setLabel( 'Nombre' )
               ->setRequired( 'true' )
               ->addFilter( 'StripTags' )
               ->addFilter( 'StringTrim' )
               ->addValidator( 'NotEmpty' )
	       ->addValidator( 'Regex', false, array( '#^([a-z A-Z0-9ñÑáéíóúÁÉÍÓÚüÜ\(\)-]+$.*)#' ) )
               ->addValidator( 'stringLength', true, array(1, 255));

    	$descripcion = new Zend_Form_Element_Text( 'descripcion' );
    	$descripcion->setLabel( 'Descripción' )
    	            ->setRequired( 'true' )
    	            ->addFilter( 'StripTags' )
    	            ->addFilter( 'StringTrim' )
    	            ->addValidator( 'NotEmpty' )
	            ->addValidator( 'Regex', false, array( '#^([a-z A-Z0-9ñÑáéíóúÁÉÍÓÚüÜ\(\)-]+$.*)#' ) )
                    ->addValidator( 'stringLength', true, array( 1, 255 ) );
 		    	
    	$precio = new Zend_Form_Element_Text( 'precio' );
    	$precio->setLabel( 'Precio' )
    	       ->setRequired( 'true' )
    	       ->addFilter( 'StripTags' )
    	       ->addFilter( 'StringTrim' )
    	       ->addValidator( 'NotEmpty' )
    	       ->addValidator( 'Float' )
               ->addValidator( 'stringLength', true, array( 4, 6 ) );

    	$existencia = new Zend_Form_Element_Text( 'existencia' );
    	$existencia->setLabel( 'Existencia' )
    	           ->setRequired( 'true' )
    	           ->addFilter( 'StripTags' )
    	           ->addFilter( 'StringTrim' )
    	           ->addValidator( 'NotEmpty' )
    	           ->addValidator( 'Int' )
                   ->addValidator( 'stringLength', true, array( 1, 3 ) );

    	$imagenActual = new Zend_Form_Element_Image('imagenActual');

		$imagen = new Zend_Form_Element_File( 'imagen' );
        $imagen->setLabel ( 'Imagen' )
	           ->setRequired ( 'true' )
	           ->addValidator( 'IsImage' )
	           ->addValidator( 'NotEmpty' )
                   ->addValidator( 'Size', false, '1024000')
                   ->addValidator( 'Extension', false, 'jpg, png')
                   ->addValidator( 'ImageSize', false, array( 'maxheight' => 2500, 'maxwidth' =>2500 ) )
                   ->setMaxFileSize( 1024000 );
               
    	$enviar = new Zend_Form_Element_Submit( 'enviar' );
    	$enviar->setAttrib( 'id', 'botonEnviar' );

    	$this->addElements( 
    	    array( $id, $nombre, $descripcion, $precio, $existencia, $imagenActual, $imagen, $enviar ) 
    	);
    }
}
