<div class="m2_box2 FL">
        	<div class="m2_tit">
            	<h3><a href="{dede:field name='typeurl'/}">{dede:field name='typename'/}</a></h3>
            	<span><a href="{dede:field name='typeurl'/}">更多</a></span>
            </div>
            <div class="m2_box3">
            	{dede:arclist row=1 titlelen=22 type='image.'}
            	<a href="[field:arcurl/]"><img src="[field:picname/]" alt="[field:fulltitle/]" /></a>
                <h4><a href="[field:arcurl /]">[field:title /]</a></h4>
                <p>[field:description function="cn_substr(@me,50)"/]...</p>
                {/dede:arclist}
            </div>
            <ul class="m2_box4">
            	{dede:arclist titlelen='40' row='6'}
                <li>·<a href="[field:arcurl /]">[field:title /]</a></li>
                {/dede:arclist}
            </ul>
        </div>