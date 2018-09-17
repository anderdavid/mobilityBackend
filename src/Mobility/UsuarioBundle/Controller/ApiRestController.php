<?php

namespace Mobility\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;


use Mobility\UsuarioBundle\Entity\usuario;




class ApiRestController extends FOSRestController
{

	private $APIREST_USUARIO_NOMBRE="nombre";
	private $APIREST_USUARIO_APELLIDO="apellido";
	private $APIREST_USUARIO_FECHA_NACIMIENTO="fechacNacimiento";
	private $APIREST_USUARIO_EDAD="edad";
	private $APIREST_USUARIO_GENERO="genero";
	private $APIREST_USUARIO_CIUDAD="ciudad";
	private $APIREST_USUARIO_EMAIL="email";
	private $APIREST_USUARIO_LOGIN="login";
	private $APIREST_USUARIO_PASSWORD="password";

	
	/**
     * @Route("/apiRestV1/")
     */
    public function indexAction(){
        return new Response("Bienvenido al modulo de apirest");
    }

    /**
     * @Rest\Get("/apiRestV1/usuario")
     */
    public function getAction(){

    	$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query ="SELECT COUNT(*) AS countUser FROM usuario";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $res=$stmt->fetchAll();

         foreach ($res as $users) {
         	$countUser=$users["countUser"];
         }



		$result = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->findAll();

		if ($result === null || $countUser==0) {
          
        	$respuesta["status"]="true";
        	$respuesta["msg"]="No hay usuarios existentes";
		
			return $respuesta;
		}else{
			$respuesta["status"]="true";
			$respuesta["countUser"]=$countUser;
			$respuesta["usuario"]=$result;
		
		 	return $respuesta;
		}
       
	}

	/**
	 * @Rest\Get("/apiRestV1/usuario/{id}")
	 */
	public function idAction($id)
	{
		$result = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);
		if ($result === null) {

			$respuesta["status"]="false";
        	$respuesta["msg"]="Usuario no encontrado";
        	
        	return $respuesta;
		}else{
			$respuesta["status"]="true";
			$respuesta["usuario"]=$result;
		
			return $respuesta;
		}
		
		
	}

	/**
	 * @Rest\Post("/apiRestV1/usuario")
	 */
	public function postAction(Request $request){

		$data = new Usuario();

		$nombre= $request->get($this->APIREST_USUARIO_NOMBRE);
		$apellido= $request->get($this->APIREST_USUARIO_APELLIDO);
		$fechaNacimiento= $request->get($this->APIREST_USUARIO_FECHA_NACIMIENTO);
		$edad= $request->get($this->APIREST_USUARIO_EDAD);
		$genero= $request->get($this->APIREST_USUARIO_GENERO);
		$ciudad= $request->get($this->APIREST_USUARIO_CIUDAD);
		$email= $request->get($this->APIREST_USUARIO_EMAIL);
		$login= $request->get($this->APIREST_USUARIO_LOGIN);
		$password= $request->get($this->APIREST_USUARIO_PASSWORD);

        if(empty($nombre) ||empty($apellido) ||empty($fechaNacimiento) 
				||empty($edad) ||empty($genero) ||empty($ciudad) ||empty($email)
				 ||empty($login) ||empty($password) ){

			$respuesta["status"]="false";
        	$respuesta["msg"]="Valores no pemitidos";
				
			return $respuesta;
		}else{

				$data->setNombre($nombre);
				$data->setApellido($apellido);
				$data->setFechacNacimiento($fechaNacimiento);
				$data->setEdad($edad);
				$data->setGenero($genero);
				$data->setCiudad($ciudad);
				$data->setEmail($email);
				$data->setLogin($login);
				$data->setPassword($password);

				$em = $this->getDoctrine()->getManager();
				$em->persist($data);
				$em->flush();


				$respuesta["status"]="true";
				$respuesta["msg"]="usuario creado";

				return $respuesta;
			}

	}

	/**
	 * @Rest\Put("/apiRestV1/usuario/{id}")
	 */
	public function putAction(Request $request,$id){

		$data =new Usuario();

		$nombre= $request->get($this->APIREST_USUARIO_NOMBRE);
		$apellido= $request->get($this->APIREST_USUARIO_APELLIDO);
		$fechaNacimiento= $request->get($this->APIREST_USUARIO_FECHA_NACIMIENTO);
		$edad=$this->diffDate($fechaNacimiento);
		$genero= $request->get($this->APIREST_USUARIO_GENERO);
		$ciudad= $request->get($this->APIREST_USUARIO_CIUDAD);
		$email= $request->get($this->APIREST_USUARIO_EMAIL);
		$login= $request->get($this->APIREST_USUARIO_LOGIN);
		$password= $request->get($this->APIREST_USUARIO_PASSWORD);

		$data= $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);

		
		if(empty($data)){

			$respuesta["status"]="false";
        	$respuesta["msg"]="Usuario no encontrado";
				
			return $respuesta;
			
		}else if(
			(!empty($nombre))||(!empty($apellido))||(!empty($fechaNacimiento))||
			(!empty($edad))||(!empty($genero))||(!empty($ciudad))||(!empty($email))||(!empty($login))||(!empty($password))
		){

			if(!empty($nombre)){

				$data->setNombre($nombre);

			}if(!empty($apellido)){

				$data->setApellido($apellido);

			}if(!empty($fechaNacimiento)){

				$data->setFechacNacimiento($fechaNacimiento);


			}if(!empty($edad)){

				$data->setEdad($edad);

			}if(!empty($ciudad)){

				$data->setCiudad($ciudad);

			}if(!empty($genero)){

				$data->setGenero($genero);

			}if(!empty($ciudad)){

				$data->setCiudad($ciudad);

			}if(!empty($email)){

				$data->setEmail($email);

			}if(!empty($login)){

				$data->setLogin($login);

			}if(!empty($password)){

				$data->setPassword($password);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();

			$respuesta["status"]="true";
        	$respuesta["msg"]="El Usuario ".$nombre." ha sido actualizado";
				
			return $respuesta;

		}else{
			$respuesta["status"]="false";
        	$respuesta["msg"]="No se pudo actualizar usuario";
				
			return $respuesta;
			
		}

	}

	/**
	 * @Rest\Delete("/apiRestV1/usuario/{id}")
	 */
	public function deleteAction($id) {
		
		$data = new Usuario;
		$sn = $this->getDoctrine()->getManager();
		$usuario = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);
		
		if (empty($usuario)) {
			$respuesta["estado"]="true";
			$respuesta["msg"]="Usuario no encontrado";
			return $respuesta;
		}
		else {
			$sn->remove($usuario);
			$sn->flush();

			$respuesta["estado"]="true";
			$respuesta["msg"]="Usuario borrado";
		
			return $respuesta;
		}
		
		
	}

	
	public function diffDate($fechaNacimiento){

		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();


		$query="SELECT TIMESTAMPDIFF(YEAR,'".$fechaNacimiento."',CURDATE()) 
		AS edad;";

		$stmt = $db->prepare($query);
  	    $stmt->execute();
        $res=$stmt->fetchAll();

         foreach ($res as $respuesta) {
         	$edadUser=$respuesta["edad"];
         }
         return $edadUser;
     
	}




}