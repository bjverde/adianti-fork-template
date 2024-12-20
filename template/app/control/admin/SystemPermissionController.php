<?php
class SystemPermissionController extends TPage
{
    public function reloadPermissions($param)
    {
        try
        {
            SystemPermissionService::reloadPermissions();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
