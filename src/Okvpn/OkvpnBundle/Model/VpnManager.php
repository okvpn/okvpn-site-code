<?php

namespace Okvpn\OkvpnBundle\Model;

use Okvpn\OkvpnBundle\Entity\VpnUser;
use Okvpn\OkvpnBundle\Helper\VpnHelper;

class VpnManager
{
    /** @var VpnHelper  */
    protected $vpnHelper;

    public function __construct(VpnHelper $vpnHelper)
    {
        $this->vpnHelper = $vpnHelper;
    }

    /**
     * @param array $listId
     */
    public function deleteVpnItemsByList(array $listId)
    {
        foreach ($listId as $itemId) {
            $vpnItem = $this->getVpnItemById($itemId);
            if ($vpnItem->getId() !== null && $this->vpnHelper->deleteIsApplicable($vpnItem)) {
                $vpnItem->setActive(false);
                $vpnItem->save();
            }
        }
    }

    /**
     * @param $id
     * @return bool|VpnUser
     */
    private function getVpnItemById($id)
    {
        $vpnItem = new VpnUser($id);
        return $vpnItem;
    }
}
