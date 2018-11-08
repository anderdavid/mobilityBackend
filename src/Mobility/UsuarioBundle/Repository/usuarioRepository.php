<?php

namespace Mobility\UsuarioBundle\Repository;

/**
 * usuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class usuarioRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllDesc(): array
    {
		/**
		 * Mostrar todos los usuarios en orden descendente
		*/
        $qb = $this->createQueryBuilder('p')
			->orderBy('p.nombre', 'ASC')
			->getQuery();
		
        return $qb->execute();
	}
}
