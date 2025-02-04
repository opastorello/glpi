<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2025 Teclib' and contributors.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

namespace Glpi\Config\LegacyConfigurators;

use Glpi\Config\ConfigProviderHasRequestTrait;
use Glpi\Config\ConfigProviderWithRequestInterface;
use Glpi\Config\LegacyConfigProviderInterface;
use Glpi\Toolbox\URL;
use Session;

final class SessionConfig implements LegacyConfigProviderInterface, ConfigProviderWithRequestInterface
{
    use ConfigProviderHasRequestTrait;

    public function execute(): void
    {
        if (!isset($_SESSION["MESSAGE_AFTER_REDIRECT"])) {
            $_SESSION["MESSAGE_AFTER_REDIRECT"] = [];
        }

        $request = $this->getRequest();

        // Manage force tab
        if ($request->query->has('forcetab')) {
            $itemtype = URL::extractItemtypeFromUrlPath($request->getPathInfo());
            if ($itemtype !== null) {
                Session::setActiveTab($itemtype, $_REQUEST['forcetab']);
            }
        }

        // Manage tabs
        if (isset($_REQUEST['glpi_tab']) && isset($_REQUEST['itemtype'])) {
            Session::setActiveTab($_REQUEST['itemtype'], $_REQUEST['glpi_tab']);
        }
        // Override list-limit if choosen
        if (isset($_REQUEST['glpilist_limit'])) {
            $_SESSION['glpilist_limit'] = $_REQUEST['glpilist_limit'];
        }
    }
}
