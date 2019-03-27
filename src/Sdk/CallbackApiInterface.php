<?php

namespace DCorePHP\Sdk;

interface CallbackApiInterface
{
    /**
     * Stop receiving any notifications. This unsubscribes from all subscribed objects ([setSubscribeCallback] and [AccountApi.getFullAccounts]).
     */
    public function cancelAll(): void;

    /**
     * Receive new block notifications. Cannot be cancelled.
     */
    public function onBlockApplied(): string;

    /**
     * Receive notifications on content update. Cannot be cancelled.
     *
     * @param string $uri content URI to monitor
     */
    public function onContentUpdate(string $uri): void;

    /**
     * Receive notifications on pending transactions. Cannot be cancelled.
     */
    public function onPendingTransaction(): void;

    /**
     * Subscribe to callbacks. Can be cancelled. with [cancelAllSubscriptions].
     *
     * @param bool $clearFilter clear current subscriptions created with [AccountApi.getFullAccounts]
     */
    public function onGlobal(bool $clearFilter): void;
}