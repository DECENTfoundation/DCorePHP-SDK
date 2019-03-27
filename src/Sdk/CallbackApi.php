<?php

namespace DCorePHP\Sdk;

class CallbackApi extends BaseApi implements CallbackApiInterface
{
    /**
     * @inheritDoc
     */
    public function cancelAll(): void
    {
        // TODO: Implement cancelAll() method.
    }

    /**
     * @inheritDoc
     */
    public function onBlockApplied(): string
    {
        // TODO: Implement onBlockApplied() method.
    }

    /**
     * @inheritDoc
     */
    public function onContentUpdate(string $uri): void
    {
        // TODO: Implement onContentUpdate() method.
    }

    /**
     * @inheritDoc
     */
    public function onPendingTransaction(): void
    {
        // TODO: Implement onPendingTransaction() method.
    }

    /**
     * @inheritDoc
     */
    public function onGlobal(bool $clearFilter): void
    {
        // TODO: Implement onGlobal() method.
    }
}