<aside class="tb-bar-section">
        <ul class="tb-nav">
			<?php foreach($options as $k=>$v):?>
                <li>	
                    <?php
                    $childs = array_flip(array_keys((array)$v));
                    $childs[key($childs)] = 'firstkey';//FIX FOR array_filter first value
                    $childs['SUB'] = ''; //REMOVE SUB CHILD
                    
                    if(count(array_filter( (array) $childs)) <= 0): ?>
                    
                        <?php if(empty($v['SUB'])):?>
                        <a href="#"><i class="icon-<?php echo $k;?>"></i><?php echo slugtotext($k);?></a>
                        <?php else:?>
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_<?php echo $k;?>" href="#sub-<?php echo $k;?>"><i class="icon-<?php echo $k;?>"></i><?php echo slugtotext($k);?></a>
                        <!--Sub-menu-->
                        <ul id="sub-<?php echo $k;?>" class="nav nav-stacked collapse">
                            <?php foreach($v['SUB'] as $sk=>$sv) :?>
                            <li>
                                <a href="<?php echo admin_url('themes.php?page=fw_theme_options&section='.$k.'&subsection='.$sk);?>">
                                    <?php echo slugtotext($sk);?>
                                </a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                        <?php endif;?>
                        
                    <?php else:?>
                        <a href="<?php echo admin_url('themes.php?page=fw_theme_options&section='.$k);?>">
                            <i class="icon-<?php echo $k;?>"></i><?php echo slugtotext($k);?>
                        </a>
                    <?php endif;?>
                </li>
			<?php endforeach;?>
        </ul><!-- Settings Bar ends -->
</aside>