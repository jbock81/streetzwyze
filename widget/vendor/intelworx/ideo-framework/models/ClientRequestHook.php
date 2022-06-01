<?php

/**
 * Description of ClientRequestHook
 *
 * Client Request Hook are hooks that are attached to the processing of a client's requests.
 *
 * @author intelWorX
 */
abstract class ClientRequestHook
{

    const HOOK_PRE_CONTROLLER = 'preController';
    const HOOK_NO_CONTROLLER = 'noController';
    const HOOK_PRE_DISPATCH = 'preDispatch';
    const HOOK_POST_DISPATCH = 'postDispatch';
    const HOOK_PRE_DISPLAY = 'preDisplay';
    const HOOK_POST_DISPLAY = 'postDisplay';
    const HOOK_SHUTDOWN = 'shutdown';

    /**
     * Callled before controller is loaded
     */
    public function preController(ClientHttpRequest $request)
    {
    }

    public function noController(ClientHttpRequest $request)
    {
    }

    /**
     * Callled before dispatch of action
     */
    public function preDispatch(ClientHttpRequest $request, \BaseController $controller)
    {
    }

    /**
     * Called when the dispatch is completed
     */
    public function postDispatch(ClientHttpRequest $request, \BaseController $controller)
    {
    }

    /**
     * Called when the view is about to be rendered
     */
    public function preDisplay(ClientHttpRequest $request, \BaseController $controller)
    {
    }

    /**
     * Called after view is rendered
     */
    public function postDisplay(ClientHttpRequest $request, \BaseController $controller)
    {
    }

    /**
     * Called when the request is completed and application is about to shutdown
     * @param ClientHttpRequest $request HTTP Reques
     * @param BaseController $controller Nullable controller, so method shoudl check.
     *
     *
     */
    public function shutdown(ClientHttpRequest $request, $controller)
    {
    }
}

