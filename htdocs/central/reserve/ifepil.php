</select>
   <INPUT TYPE=HIDDEN VALUE="<?php echo $arrHttp["LastKey"]?>" NAME="LastKey">
   <br>
 </td>
 <td width=10>&nbsp;</td>
 <td class=textbody03 >Para ir a otra secci�n del diccionario, escriba sus primeras letras
 <input type=text name="IR_A" size=10> y haga clic sobre &nbsp;&nbsp;
 <strong><a href="javascript:EjecutarBusqueda(this,3)" class=boton>continuar</a></strong>&nbsp;&nbsp;.
<?php
// Si existe $arrHttp["Target"] no se realiza la b�squeda directamente
	if (!isset($arrHttp["Target"])) echo "<p>Haca clic sobre&nbsp;&nbsp; <strong><a href=\"javascript:EjecutarBusqueda(this,1)\" class=boton>Buscar</a></strong> &nbsp;&nbsp; para ejecutar la b�squeda sobre los t�rminos seleccionados";

    echo "<p><strong><a href=\"javascript:EjecutarBusqueda(this,2)\" class=boton>Enviar t�rminos</a></strong> &nbsp;&nbsp; Para enviar los t�rminos seleccionados al formulario de b�squeda";
?>
            </td>
          </tr>
          <tr>
            <td align=center>&nbsp;&nbsp;<strong><a href="javascript:EjecutarBusqueda(this,4)" class=boton>M�s t�rminos</a>&nbsp;&nbsp;
            </td>
          </tr>
        </table>
      </td>
  </table>

</form>
</div>
</div>


