001 0 "CN_"v1 
1 0 "CN_"v1 
3005 0 mpu,if v3005='n' then 'LDR_Novo registro' fi, if v3005='a' then 'LDR_Aumentado' fi, if v3005='c' then 'LDR_Alterado e revisado' fi, if v3005='d' then 'LDR_Deletado' fi 
41 0 (|ID_|v41^a|%|/), 
08 0 if v3006='a' and v3007='m' then (|FIX0_|v8.6|%|/), (|FIX6_|v8*6.1|%|/), (|FIX7_|v8*7.4|%|/), (|FIX11_|v8*11.4|%|/), (|FIX15_|v8*15.3|%|/), (|FIX18_|v8*18.4|%|/), (|FIX22_|v8*22.1|%|/), (|FIX23_|v8*23.1|%|/), (|FIX24_|v8*24.4|%|/),(|FIX28_|v8*28.1|%|/), (|FIX29_|v8*29.1|%|/), (|FIX30_|v8*30.1|%|/), (|FIX31_|v8*31.1|%|/), (|FIX32_|v8*32.1|%|/), (|FIX33_|v8*33.1|%|/), (|FIX34_|v8*34.1|%|/), (|FIX35_|v8*35.3|%|/),(|FIX38_|v8*38.1|%|/), (|FIX39_|v8*39.1|%|/), fi 
999 0 'N:',f(mfn,1,0) 
050 5 mpu,if p(v050^a) then '/ST_/',v50^a,"."v50^b,"."v50^c,"."v50^d,"."v50^e|%|,fi, 
082 5 mpu,if p(v082^a) then '/ST_/',v82^a,"/"v82^b,"/"v82^2|%|,fi, 
082 5 mpu,if p(v090^b) then '/ST_/',v90^b|%|,fi, 
090 5 mpu,if p(v090^a) then '/CH_/',(v90^a,"/"v90^b,"/"v90^c,"/"v90^d|%|),fi, 
090 0 mpu,(|COL_|v90^a|%|/) 
020 5 mpu,if p(v020^a) then '/IS_/',v20^a|%|/,fi, 
020 5 mpu,if p(v022^a) then '/IS_/',v22^a|%|/,fi, 
100 0 mpu,(|AUI_|v100|%|/) 
100 0 mpu,(|AU_|v100^a|%|/) 
100 8 mpu,'|TW_|'(v100^a|%|/),(v100^c|%|/),(v100^q|%|/),(v100^4|%|/) 
100 8 mpu,'|PN_|'(v100^c|%|/), 
600 0 mpu,(|AU_|v600^a|%|/) 
600 8 mpu,'|TW_|'(v600^a|%|/),(v600^c|%|/),(v600^q|%|/), 
700 0 mpu,(|AU_|v700^a|%|/) 
700 0 mpu,(|AU_|v700^a,v700^c,v700^t,v700^q,v700^4) 
700 8 mpu,'|TW_|'(v700^a|%|/),(v700^c|%|/),(v700^q|%|/),(v700^4|%|/) 
110 5 mpu,if p(v110^a) then '/AI_/',(v110^a,v110^b|%|/),fi, 
710 5 mpu,if p(v710^a) then '/AI_/',(v710^a,v710^b|%|/),fi, 
111 5 mpu,if p(v111^a) then '/CF_/',(v111^a,v111^c,v111^d,v111^n|%|/),fi, 
711 5 mpu,if p(v711^a) then '/CF_/',(v711^a,v711^c,v711^d,v711^n|%|/),fi, 
240 5 mpu,if p(v240^a) then '/TI_/',(v240^a|%|/),fi 
242 5 mpu,if p(v242^a) then '/TI_/',(v242^a,' ',v242^b|%|/),fi 
245 5 mpu,if p(v245^a) then '/TI_/',(v245^a,' ',v245^b|%|/),fi 
245 0 mpu,(|TII_|v245|%|/) 
245 0 mpu,(|TI_|v245|%|/) 
245 0 mpl,(if v245*1.1='0' then 'TI_'v245^a fi), 
245 0 mpl,(if v245*1.1='1' then 'TI_'v245^a*1 fi), 
245 0 mpl,(if v245*1.1='2' then 'TI_'v245^a*2 fi), 
245 0 mpl,(if v245*1.1='3' then 'TI_'v245^a*3 fi), 
245 0 mpl,(if v245*1.1='4' then 'TI_'v245^a*4 fi), 
740 5 mpu,if p(v740^a) then '/TI_/',(v740^a|%|/),fi 
773 5 mpu,if p(v773^t) then '/TI_/',(v773^t|%|/),fi 
260 5 mpu,if p(v260^a) then '/PA_/',(v260^a|%|/),fi 
260 5 mpu,if p(v260^b) then '/ED_/',(v260^b|%|/),fi 
260 5 mpu,if p(v260^c) then '/DA_/',(v260^c|%|/),fi 
490 5 mpu,if p(v440^a) then '/MS_/',(v440^a|%|/),fi 
490 5 mpu,if p(v490^a) then '/MS_/',(v490^a|%|/),fi 
600 5 mpu,if p(v600^a) then '/AS_/',(v600^a,v600^b,v600^c,v600^d,| - |v600^v,| - |v600^x,| - |v600^y,| - |v600^z|%|/),fi, 
610 5 mpu,if p(v610^a) then '/CS_/',(v610^a,v610^b,v610^c,v610^d,| - |v610^v,| - |v610^x,| - |v610^y,| - |v610^z|%|/),fi, 
611 5 mpu,if p(v611^a) then '/ES_/',(v611^a,v611^c,v611^d,v611^e,| - |v611^v,| - |v611^x,| - |v611^y,| - |v611^z|%|/),fi, 
650 5 mpu,if p(v650^a) then '/MA_/',(v650^a,| - |v650^v,| - |v650^x,| - |v650^y,| - |v650^z|%|/),fi, 
650 8 mdu,if p(v650^a) then '|MA_|',(v650^a|%|/),fi, 
651 5 mpu,if p(v651^a) then '/DG_/',(v651^a,| - |v651^v,| - |v651^x,| - |v651^y,| - |v651^z|%|/),fi, 
655 5 mpu,if p(v655^a) then '/GF_/',(v655^a,| - |v655^v,| - |v655^x,| - |v655^y,| - |v655^z|%|/),fi, 
999 8 mdu,if p(v100^a) then '|TW_|',(v100*4|%|/),fi, 
999 8 mdu,if p(v100^a) then '|TW_|',(v100^a|%|/),fi, 
999 8 mdu,if p(v110^a) then '|TW_|',(v110*4|%|/),fi, 
999 8 mdu,if p(v111^a) then '|TW_|',(v111*4|%|/),fi, 
999 8 mdu,if p(v240^a) then '|TW_|',(v240*4|%|/),fi, 
999 8 mdu,if p(v245^a) then '|TW_|',(v245*4|%|/),fi, 
999 8 mdu,if p(v245^a) then '|TW_|',(v245^a|%|/),fi, 
999 8 mdu,if p(v245^a) then '|TT_|',(v245*4|%|/),fi, 
999 8 mdu,if p(v246^a) then '|TW_|',(v246*4|%|/),fi, 
999 8 mdu,if p(v490^a) then '|TW_|',(v490*4|%|/),fi, 
999 8 mdu,if p(v500^a) then '|TW_|',(v500*4|%|/),fi, 
999 8 mdu,if p(v505^a) then '|TW_|',(v505*4|%|/),fi, 
999 8 mdu,if p(v520^a) then '|TW_|',(v520*4|%|/),fi, 
999 8 mdu,if p(v533^a) then '|TW_|',(v533*4|%|/),fi, 
999 8 mdu,if p(v600^a) then '|TW_|',(v600*4|%|/),fi, 
999 8 mdu,if p(v610^a) then '|TW_|',(v610*4|%|/),fi, 
999 8 mdu,if p(v611^a) then '|TW_|',(v611*4|%|/),fi, 
999 8 mdu,if p(v650^a) then '|TW_|',(v650*4|%|/),fi, 
999 8 mdu,if p(v651^a) then '|TW_|',(v651*4|%|/),fi, 
999 8 mdu,if p(v653^a) then '|TW_|',(v653*4|%|/),fi, 
999 8 mdu,if p(v655^a) then '|TW_|',(v655*4|%|/),fi, 
999 8 mdu,if p(v690^a) then '|TW_|',(v690*4|%|/),fi, 
999 8 mdu,if p(v520^a) then '|TW_|',(v520*4|%|/),fi, 
999 8 mdu,if p(v740^a) then '|TW_|',(v740*4|%|/),fi, 
999 8 mdu,if p(v773^t) then '|TW_|',(v773*4|%|/),fi, 
990 0 (|NI=|v900^n/) 
901 0 mpu,(if iocc<100 then |UB_|v900^o|%|/ else break fi)/(if iocc<100 then v900^o|%|/ else break fi) 
902 0 mpu,(if iocc<100 then |PR_|v900^r|%|/ else break fi) 
949 5 mpu,'|TW_|'(v949^a|%|/), 
949 0 mpu,'|RE_|'(v949^a|%|/), 
949 0 mpu,'|IN_|'(v949^a|%|/), 
949 0 (|IN_|v949^a|%|/) 
949 5 mpu,'|RE_|'(v949^b|%|/), 
949 5 mpu,'|RE_|'(v949^c|%|/), 
949 5 mpu,'|AQ_|'(v949^c|%|/), 
949 5 mpu,'|RE_|'(v949^d|%|/), 
949 5 mpu,'|RE_|'(v949^e|%|/), 
949 5 mpu,'|RE_|'(v949^f|%|/), 
949 5 mpu,'|RE_|'(v949^g|%|/), 
949 5 mpu,'|RE_|'(v949^h|%|/), 
949 5 mpu,'|RE_|'(v949^j|%|/), 
949 5 mpu,'|RE_|'(v949^k|%|/), 
949 5 mpu,'|RE_|'(v949^l|%|/), 
949 5 mpu,'|RE_|'(v949^w|%|/), 
949 5 mpu,'|RE_|'(v949^i|%|/), 
980 5 mpu,'|OP_|'(v980^o|%|/), 
980 5 mpu,'|OP_|'(v996^a|%|/), 
980 5 mpu,'|DOP_|'(v980^d.8|%|/), 
