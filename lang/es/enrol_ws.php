<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'enrol_ws', language 'es'.
 *
 * @package    enrol_ws
 * @copyright  2017 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['ws:config'] = 'Configurar matricula desde WS REST';
$string['ws:manage'] = 'Gestionar matricula de usuarios con WS REST';
$string['pluginname'] = 'Matricula desde WS REST';
$string['pluginname_desc'] = 'El componente de matricula WS REST permite matricular estudiantes masivamente vía servicio web tipo REST, en la administración del curso, para un usuario con permisos adecuados tales como profesor.';
$string['status'] = 'Habilitar matricula desde WS';
$string['status_desc'] = 'Permite acceso a un curso de los usuarios matriculados. Debe estar habilitado para poder realizar matrículas conectando con el servicio web.';
$string['status_help'] = 'Esta configuración determina cuando los usuarios pueden ser matriculados utilizando el servicio web, mediante un enlace en la administración del curso, para usuarios con permisos de matricular en el curso.';
$string['statusenabled'] = 'Habilitado';
$string['statusdisabled'] = 'Deshabilitado';
$string['unenrol'] = 'Desmatricular usuario';
$string['enrolusers'] = 'Matricular usuario';

$string['title_ws'] = 'Matrícula masiva desde sistema externo';
$string['msg_empty_code'] = 'Este curso requiere un código para matricular los participantes. Dicho código debe ser definido en el Número Id del curso.';
$string['restart_reminder'] = 'Si no desea mantener la información actual, debe reiniciar el curso antes de realizar la matrícula, para ello seleccione el enlace "<a href="{$a}">Reiniciar curso</a>".';
$string['course_code'] = 'Código del curso: {$a}';
$string['search'] = 'Consultar usuarios';
$string['enrol'] = 'Matricular usuarios SIN notificar';
$string['send_message'] = 'Matricular usuarios Y notificarlos';
$string['event_enrol_search'] = 'Consulta de usuarios a matricular';
$string['uri'] = 'URI';
$string['uri_key'] = 'URI para la conexi&oacute;n al servicio Web que da respuesta a las matrículas';
$string['method'] = 'Método';
$string['method_key'] = 'Método que se utiliza para la conexi&oacute;n establecida por el Servicio Web';
$string['token'] = 'Token';
$string['token_key'] = 'Token de conexión con el servicio';
$string['msg_bad_response'] = 'La respuesta obtenida del servicio no es válida';
$string['msg_not_students'] = 'No se encontraron usuarios para matricular';
$string['new_users'] = 'Usuarios por matricular';
$string['msg_error_not_new_students'] = 'No hay usuarios por matricular o desmatricular en este curso';
$string['old_users'] = 'Usuarios previamente matriculados';
$string['msg_error_not_old_students'] = 'No hay usuarios matriculados actualmente de los obtenidos por la consulta';
$string['msg_error_manual_not_available'] = 'La matricula manual no está habilitada para este curso.';
$string['msg_error_not_create_user'] = 'El usuario {$a} no ha podido ser creado';
$string['msg_successful_enrol'] = 'La matrícula ha sido realizada exitosamente';
$string['msg_authpreventaccountcreation'] = 'Tenga en cuenta que en este momento no se pueden crear cuentas al matricular usuarios, si algún usuario no existe en la plataforma actualmente entonces no podrá ser matriculado.';
