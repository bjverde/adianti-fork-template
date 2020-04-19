<?php
class AdiantiMenuBuilder
{
    public static function parse($file, $theme)
    {
        switch ($theme)
        {
            case 'theme3':
                ob_start();
                $callback = array('SystemPermission', 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents($file));
                $menu = new TMenu($xml, $callback, 1, 'treeview-menu', 'treeview', '');
                $menu->class = 'sidebar-menu';
                $menu->id    = 'side-menu';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
            break;
            case 'theme3_v4':
                    ob_start();
                    $callback = array('SystemPermission', 'checkPermission');
                    $xml = new SimpleXMLElement(file_get_contents($file));
                    $menu = new TMenu($xml, $callback, 1, 'treeview-menu', 'treeview', '');
                    $menu->class = 'sidebar-menu';
                    $menu->id    = 'side-menu';
                    $menu->show();
                    $menu_string = ob_get_clean();
                    return $menu_string;
            break;
            case 'theme3_h':
                ob_start();
                $callback = array('SystemPermission', 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents($file));
                //$menu = new TMenu($xml, $callback,1,'navbar-nav mr-auto','nav-item dropdown','dropdown-item');
                //$menu = new TMenu($xml, $callback, 1, 'nav-item dropdown');
                //$menu = new TMenu($xml, $callback, 1, 'nav-item dropdown','dropdown');
                //$menu = new TMenu($xml, $callback, 1, 'nav-item dropdown','dropdown');
                $menu = new TMenu($xml, $callback, 1,'dropdown-menu','dropdown','dropdown-toggle');
                $menu->id    = 'main-menu-top';
                $menu->show();
                $menu_string = ob_get_clean();
                
                $menu_string = str_replace('class="dropdown-menu level-1" id="main-menu-top"', 'class="nav navbar-nav" id="main-menu-top"', $menu_string);
                //$menu_string = str_replace('<a href="', '<a class="dropdown-item" href="', $menu_string);
                return $menu_string;
            break;
            default:
                ob_start();
                $callback = array('SystemPermission', 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents($file));
                $menu = new TMenu($xml, $callback, 1, 'ml-menu', 'x', 'menu-toggle waves-effect waves-block');
                
                $li = new TElement('li');
                $li->{'class'} = 'active';
                $menu->add($li);
                
                $li = new TElement('li');
                $li->add('MENU');
                $li->{'class'} = 'header';
                $menu->add($li);
                
                $menu->class = 'list';
                $menu->style = 'overflow: hidden; width: auto; height: 390px;';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
        }
    }
}