<?php

namespace Pterodactyl\Http\Controllers\Base;

use Illuminate\Http\Request;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Contracts\Repository\UserRepositoryInterface;

class ThemeController extends Controller
{
    /**
     * @var \Prologue\Alerts\AlertsMessageBag
     */
    private $alert;

    /**
     * @var \Pterodactyl\Contracts\Repository\UserRepositoryInterface
     */
    private $repository;

    /**
     * ThemeController constructor.
     *
     * @param \Prologue\Alerts\AlertsMessageBag                         $alert
     * @param \Pterodactyl\Contracts\Repository\UserRepositoryInterface $repository
     */
    public function __construct(AlertsMessageBag $alert, UserRepositoryInterface $repository)
    {
        $this->alert = $alert;
        $this->repository = $repository;
    }

    /**
     * Store the user theme selection.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     * @throws \Pterodactyl\Exceptions\Repository\RecordNotFoundException
     */
    public function update(Request $request)
    {
        $this->repository->update($request->user()->id, [
            'theme' => $request->input('theme', 'pterodactyl'),
        ]);

        $this->alert->success(trans('base.account.theme_updated'))->flash();

        return redirect()->route('account');
    }
}
