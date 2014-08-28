<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/28/14
 * Time: 12:14 PM
 */
namespace Auth\View\Helper;;

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

class AuthServiceHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function __invoke()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('AuthService');

    }
}