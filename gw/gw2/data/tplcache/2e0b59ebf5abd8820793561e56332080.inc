<li><a href="[field:arcurl /]" target="_blank" class="cBlack">[field:title /]</a>
			  <font color="red">
                [field:senddate runphp='yes']
                $nowtime = time();
                if(time() - @me < (3600*24)) @me = "(New)";
                else @me = "";
                [/field:senddate]
                [field:click runphp='yes']
                if(@me>500) @me = "(Hot)";
                else @me = "";
                [/field:click]
              </font>
	</li>