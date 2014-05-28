<?php
//
// Bu fonksiyon, degiskenlerdeki zararli karakterleri temizlemek icindir
// Dosyanizda, include_once "koruma.php";  satirini ekledikten sonra
// $degisken = secure_var($........); 
// ya da MySQL verisi olarak kullanilacaksa
// $degisken = secure_var($........, true); 
// seklinde kullanilmalidir
//
function secure_var($var, $formysql=false) {
   // Bu fonksiyon, STRING icerisinde olmamasi gereken kelime ve karakterleri silmek icindir
   $temizle = array('<', '>', '|', '/', '+', ' and ', ' or ', '/etc', '..', '=', '0x', 'union ', 'UNION ', 'SELECT ', 'INSERT ', 'insert ', 'UPDATE ', 'update ', '%', ' INTO ', ' into ', ' FROM ', ' from ');
   $yenikod = str_replace($temizle, '', $var);
   // Eger veri MySQL'e gonderilecek ise MySQL koruyucu da kullanilmalidir
   if ($formysql) { $yenikod = mysql_real_escape_string($yenikod); }
   return $yenikod;
}
?>
