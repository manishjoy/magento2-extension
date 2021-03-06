<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  2011-2016 ESS-UA [M2E Pro]
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Amazon\Template\ShippingTemplate;

use Ess\M2ePro\Controller\Adminhtml\Amazon\Template;

class Delete extends Template
{
    public function execute()
    {
        $ids = $this->getRequestIds();

        if (count($ids) == 0) {
            $this->getMessageManager()->addError($this->__('Please select Item(s) to remove.'));
            return $this->_redirect('*/amazon_template/index');
        }

        $deleted = $locked = 0;
        foreach ($ids as $id) {
            /** @var \Ess\M2ePro\Model\Amazon\Template\ShippingTemplate $template */
            $template = $this->activeRecordFactory->getObjectLoaded(
                'Amazon\Template\ShippingTemplate', $id, NULL, false
            );

            if (is_null($template)) {
                continue;
            }

            if ($template->isLocked()) {
                $locked++;
            } else {
                $template->delete();
                $deleted++;
            }
        }

        $tempString = $this->__('%amount% record(s) were successfully deleted.', $deleted);
        $deleted && $this->getMessageManager()->addSuccess($tempString);

        $tempString  = $this->__('%amount% record(s) are used in Listing(s).', $locked) . ' ';
        $tempString .= $this->__('Policy must not be in use to be deleted.');
        $locked && $this->getMessageManager()->addError($tempString);

        return $this->_redirect('*/amazon_template/index');
    }
}