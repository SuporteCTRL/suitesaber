../../../cgi-bin/mx  marc iso=bpp.iso -all now

echo "ISO CRIADO";

../../../cgi-bin/mx  iso=bpp.iso create=marc convert=ansi  -all now

echo "Base Ansi Criada";

../../../cgi-bin/retag marc retag.tab

echo "Campos 3000 criados";

echo "Importar os 36 registros criados após";