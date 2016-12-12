<?php

/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2016   César Sáez Rodríguez    NATHOO@lacalidad.es
 * Copyright (C) 2016   Carlos García Gómez     neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Description of opciones_factura_detallada
 *
 * @author César
 */
class opciones_ImpresionRSV extends fs_controller
{
   public $ImpresionRSV_setup;
   public $colores;
   
   public function __construct()
   {
      parent::__construct(__CLASS__, 'ImpresionRSV', 'admin', FALSE, FALSE);
   }
   
   protected function private_core()
   {
      $this->check_menu();
      $this->share_extension();

      $this->colores = array("gris", "rojo", "verde", "azul","naranja","amarillo","marron", "blanco");
      
      /// cargamos la configuración
      $fsvar = new fs_var();
      $this->ImpresionRSV_setup = $fsvar->array_get(
         array(
            'ImpresionRSV_color' => 'azul'
         ),
         FALSE
      );
      
      if( isset($_POST['ImpresionRSV_setup']) )
      {
         $this->ImpresionRSV_setup['ImpresionRSV_color'] = $_POST['color'];
         
         if( $fsvar->array_save($this->ImpresionRSV_setup) )
         {
            $this->new_message('Datos guardados correctamente.');
         }
         else
            $this->new_error_msg('Error al guardar los datos.');
      }
   }
   
   private function share_extension()
   {
      $fsext = new fs_extension();
      $fsext->name = 'opciones_ImpresionRSV';
      $fsext->from = __CLASS__;
      $fsext->to = 'admin_empresa';
      $fsext->type = 'button';
      $fsext->text = '<span class="glyphicon glyphicon-print" aria-hidden="true"></span> &nbsp; Factura Detallada RSV';
      $fsext->save();
   }
   
   /**
    * Activamos las páginas del plugin.
    */
   private function check_menu()
   {
      if( file_exists(__DIR__) )
      {
         /// activamos las páginas del plugin
         foreach( scandir(__DIR__) as $f)
         {
            if( is_string($f) AND strlen($f) > 0 AND !is_dir($f) AND $f != __CLASS__.'.php' )
            {
               $page_name = substr($f, 0, -4);
               
               require_once __DIR__.'/'.$f;
               $new_fsc = new $page_name();
                  
               if( !$new_fsc->page->save() )
               {
                  $this->new_error_msg("Imposible guardar la página ".$page_name);
               }
               
               unset($new_fsc);
            }
         }
      }
      else
      {
         $this->new_error_msg('No se encuentra el directorio '.__DIR__);
      }
   }
}
