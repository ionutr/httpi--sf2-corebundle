<?php

namespace Httpi\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {

    protected $extendsTemplate;

    protected $loginTemplate = 'HttpiCoreBundle:Security:login.html.twig';

    protected $error = false;

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $this->error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $this->error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $response = array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $this->error
        );

        if (!is_null($this->extendsTemplate)) {
            $response['extendsTemplate'] = $this->extendsTemplate;
        }

        return $this->render($this->loginTemplate, $response);
    }

}