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


//MobilityUsuarioBundle:usuario

class ApiRestController extends FOSRestController
{
	/**
     * @Route("/apiRestV1/")
     */
    public function indexAction()
    {
        return new Response("Bienvenido al modulo de apirest");
    }

    /**
     * @Rest\Get("/apiRestV1/usuario")
     */
    public function getAction()
    {
		$restresult = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->findAll();

        if ($restresult === null) {
          return new View("there are no users exist", Response::HTTP_NOT_FOUND);
		}
        $respuesta["status"]="true";
		$respuesta["usuario"]=$restresult;
		
		return $respuesta;
	}

	/**
	 * @Rest\Get("/apiRestV1/usuario/{id}")
	 */
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);
		if ($singleresult === null) {
			return new View("user not found", Response::HTTP_NOT_FOUND);
		}
		
		$respuesta["status"]="true";
		$respuesta["usuario"]=$singleresult;
		
		return $respuesta;
	}

	

	/**
	 * @Rest\Post("/apiRestV1/usuario")
	 */
	public function postAction(Request $request){

		$data = new Usuario();

			$nombre= $request->get('nombre');
			$apellido= $request->get('apellido');
			$fechacNacimiento= $request->get('fechacNacimiento');
			$edad= $request->get('edad');
			$genero= $request->get('genero');
			$ciudad= $request->get('ciudad');
			$email= $request->get('email');
			$login= $request->get('login');
			$password= $request->get('password');

			if(empty($nombre) ||empty($apellido) ||empty($fechacNacimiento) ||empty($edad) ||
			 	empty($genero) ||empty($ciudad) ||empty($email) ||empty($login) ||
			 	empty($password) ){
				return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
			}

			 $data->setNombre($nombre);
			 $data->setApellido($apellido);
			 $data->setFechacNacimiento($fechacNacimiento);
			 $data->setEdad($edad);
			 $data->setGenero($genero);
			 $data->setCiudad($ciudad);
			 $data->setEmail($email);
			 $data->setLogin($login);
			 $data->setPassword($password);
			 
			 $em = $this->getDoctrine()->getManager();
			 $em->persist($data);
			 $em->flush();

			 return new View("User Added Successfully", Response::HTTP_OK);


	}

	/**
	 * @Rest\Put("/apiRestV1/usuario/{id}")
	 */
	public function putAction(Request $request,$id){



		$data =new Usuario();

		$nombre= $request->get('nombre');
		$apellido= $request->get('apellido');
		$fechacNacimiento= $request->get('fechacNacimiento');
		$edad= $request->get('edad');
		$genero= $request->get('genero');
		$ciudad= $request->get('ciudad');
		$email= $request->get('email');
		$login= $request->get('login');
		$password= $request->get('password');

		//return new View("PUT");
		

		

		
		$data= $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);

		//return new View("request ".$request);

		

		if(empty($data)){
			return new View("Usuario no ecncontrado");
		}

		else if(
			(!empty($nombre))||(!empty($apellido))||(!empty($fechacNacimiento))||(!empty($edad))||(!empty($genero))||(!empty($ciudad))||(!empty($email))||(!empty($login))||(!empty($password))
			){

			if(!empty($nombre)){

				$data->setNombre($nombre);

			}
			if(!empty($apellido)){

				$data->setApellido($apellido);

			}
			if(!empty($fechacNacimiento)){

				$data->setFechacNacimiento($fechacNacimiento);

			}
			if(!empty($edad)){

				$data->setEdad($edad);

			}
			if(!empty($ciudad)){

				$data->setCiudad($ciudad);

			}
			
			if(!empty($genero)){

				$data->setGenero($genero);

			}
			if(!empty($ciudad)){

				$data->setCiudad($ciudad);

			}
			if(!empty($email)){

				$data->setEmail($email);

			}

			if(!empty($login)){

				$data->setLogin($login);

			}
			if(!empty($password)){

				$data->setPassword($password);
			}
			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();
			return new View("User Updated Successfully", Response::HTTP_OK);
		}else{
			return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
		}




	}

	/**
	 * @Rest\Delete("/apiRestV1/usuario/{id}")
	 */
	public function deleteAction($id) 
	{
		$data = new Usuario;
		$sn = $this->getDoctrine()->getManager();
		$usuario = $this->getDoctrine()->getRepository('MobilityUsuarioBundle:usuario')->find($id);
		
		if (empty($usuario)) {
			return new View("usuario no encontrado", Response::HTTP_NOT_FOUND);
		}
		else {
			$sn->remove($usuario);
			$sn->flush();
		}
		
		return new View("usuario borrado", Response::HTTP_OK);
	}


}