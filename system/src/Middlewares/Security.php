<?php namespace Drafterbit\System\Middlewares;

use Drafterbit\Extensions\User\Auth\Exceptions\UserNotAuthorizedException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Drafterbit\Component\Routing\Router as RouteManager;
use Drafterbit\Component\Session\SessionManager;
use Drafterbit\Framework\Application;

class Security implements HttpKernelInterface {

	/**
	 * The wrapped kernel implementation.
	 *
	 * @var \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	protected $kernel;

	/**
	 * Darafterbit Application
	 *
	 * @var \Drafterbit\Framework\Application
	 */
	protected $app;

	/**
	 * The router.
	 *
	 * @var \Drafterbit\Component\Kernel\Routing\Router
	 */
	protected $router;

	/**
	 * The session manager.
	 *
	 * @var \Drafterbit\Component\Kernel\Sessions\SessionManager
	 */
	protected $session;

	/**
	 * Create a new session middleware.
	 *
	 * @param  \Symfony\Component\HttpKernel\HttpKernelInterface  $app
	 * @param  \Drafterbit\Component\Routing\Router  $router
	 */
	public function __construct(HttpKernelInterface $kernel, Application $app, SessionManager $session, RouteManager $router)
	{
		$this->kernel = $kernel;
		$this->app = $app;
		$this->session = $session;
		$this->router = $router;
	}

	/**
	 * Handle the given request and get the response.
	 *
	 * @implements HttpKernelInterface::handle
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @param  int   $type
	 * @param  bool  $catch
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
	{
        if($access = $this->app->getCurrentRoute()->getOption('access')) {
        	try {

        		$auth = $this->app->getExtension('user')->model('Auth');
        		$auth->restrict($access);

        	} catch(UserNotAuthorizedException $e) {

        		$referer = $this->app['input']->headers('referer') ? 
        			$this->app['input']->headers('referer') : admin_url('dashboard');
        		
        		$message = $e->getMessage();
        		$this->session->getFlashBag()->add('messages', array('text' => $message, 'type' => 'error'));

        		return redirect($referer);
        	}
        }
        
        if($this->app->getCurrentRoute()->getOption('csrf')) {

            $csrfToken = $this->session->get('_token');
            $csrfInput = $request->get('csrf');

            if($csrfToken !== $csrfInput) {
                throw new \RuntimeException("invalid session");
            }
        }

		$response = $this->kernel->handle($request, $type, $catch);
        
		return $response;
	}
}