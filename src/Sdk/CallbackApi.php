<?php

namespace DCorePHP\Sdk;

class CallbackApi extends BaseApi implements CallbackApiInterface
{
    /**
     * @inheritdoc
     */
    public function cancelAll(): void
    {
        // TODO: Implement cancelAll() method.
    }

    /**
     * @inheritdoc
     */
    public function onBlockApplied(): string
    {
        // TODO: Implement onBlockApplied() method.
    }

    /**
     * @inheritdoc
     */
    public function onContentUpdate(string $uri): void
    {
        // TODO: Implement onContentUpdate() method.
    }

    /**
     * @inheritdoc
     */
    public function onPendingTransaction(): void
    {
        // TODO: Implement onPendingTransaction() method.
    }

    /**
     * @inheritdoc
     */
    public function onGlobal(bool $clearFilter): void
    {
        // TODO: Implement onGlobal() method.
    }
}