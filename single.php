<?php get_header(); ?>
<?php if(get_option('king_single_index') && get_option('king_single_index')=='开启'){ ?>
<div class="index-div">
    <div style="padding:0px 25px">
        <h4 style="font-weight: 600;margin: 0px;"><i class="czs-choose-list-l"></i> 文章引索</h4>
    </div>
    <ul id="article-index" class="index-ul">
        <li></li>
        <li style="margin: 0 25px;     margin-bottom: 10px;     height: 25px;     background: #f1f2f3; ">
        <li
            style="     margin: 0 25px;     margin-bottom: 10px;     height: 25px;     background: #f1f2f3;     width: 70%; ">
        <li style="     margin: 0 25px;     margin-bottom: 10px;     height: 25px;     background: #f1f2f3; ">
        <li
            style="     margin: 0 25px;     margin-bottom: 10px;     height: 25px;     width: 50%;     background: #f1f2f3; ">
    </ul>
</div>
<?php } ?>
<?php setPostViews($post->ID); ?>

<article class="article reveal" id="lightgallery">
    <div id="load">
        <header class="article-header">
            <span class="badge badge-pill badge-danger single-badge"><a href="<?php echo site_url() ?>"
                    style="text-decoration:none"><i class="czs-read-l" style="margin-right:5px;"></i>站点文章</a></span>
            <span class="badge badge-pill badge-danger single-badge" style="margin-left: 10px;"><a :href="cate_url"
                    style="text-decoration: none;color: #888;letter-spacing: .5px;"
                    v-html="cate">分类目录</a></span>

            <h2 class="single-h2"
                style="height: 50px;width: 100%;background: rgba(238, 238, 238, 0.81);color:rgba(238, 238, 238, 0.81)">
            </h2>
            <div class="article-list-footer"
                style="height: 25px;background: rgb(246, 247, 248);width: 80%;margin-top: 15px;color:rgb(246, 247, 248)">
            </div>
            <div class="single-line"></div>
        </header>

        <div class="article-content">
            <?php if(!post_password_required()){ ?>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 100%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 90%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 95%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 90%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 90%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 95%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 90%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 100%;     height: 20px; "></p>
            <p style="     display: block;     background: rgb(246, 247, 248);     width: 100%;     height: 20px; "></p>
        </div>
        <?php }else{ ?>
        <?php the_content(); ?>
        <?php } ?>
        <div class="article-comments" id="article-comments">
            <?php if ( comments_open() || get_comments_number() ) :
                                    comments_template();
                                    endif;
                                ?>
        </div>
    </div>
</article>























<script>
$(document).ready(function() { //避免爆代码


    var post_info = new Vue({ //axios获取顶部信息
        el: '#load',
        data() {
            return {
                posts: null,
                loading: true, //v-if判断显示占位符
                errored: true,
                cate: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                cate_url: ''
            }
        },
        mounted() {

            //获取文章
            axios.get('<?php echo site_url() ?>/wp-json/wp/v2/posts/' + <?php echo $post->ID; ?>)
                .then(response => {
                    this.posts = response.data;
                })
                .catch(e => {
                    this.errored = false
                })
                .then(() => {
                    this.loading = false;
                    this.cate = this.posts.post_categories[0].name;
                    this.cate_url = this.posts.post_categories[0].link;

                    $('.real').css('display', 'block');

                    <?php if(post_password_required()){ ?>
                    $('.article-content').attr('style', '');
                    <?php }else{ ?>
                    $('.article-content').html(this.posts.content.rendered).attr('style', '');
                    <?php } ?>

                    $('.single-h2').html(this.posts.post_metas.title.replace('密码保护：', '')).attr('style',
                        '');
                    $('.article-list-footer').html('<span class="article-list-date">' + this.posts
                        .post_date +
                        '</span><span class="article-list-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</span><span class="article-list-minutes">' +
                        this.posts.post_metas.views + '&nbsp;Views</span>').attr('style', '');


                    /* 文章目录 */

                    var h = 0;
                    var pf = 23;
                    var i = 0;
                    $('#article-index').html('');
                    var count_ti = count_in = count_ar = count_sc = count_hr = count_e = 1;
                    var offset = new Array;
                    var min = 0;
                    var c = 0;
                    var icon = '';

                    //获取最高级别h标签
                    $(".article-content>:header").each(function() {
                        h = $(this).eq(0).prop("tagName").replace('H', '');
                        if (c == 0) {
                            min = h;
                            c++;
                        } else {
                            if (h <= min) {
                                min = h;
                            }
                        }
                    });

                    //获取h标签内容
                    $(".article-content>:header").each(function() {
                        h = $(this).eq(0).prop("tagName").replace('H', ''); //标签级别
                        for (i = 0; i < Math.abs(h - min); ++i) { //偏移程度
                            pf += 10;
                        }
                        if (pf !== 23) { //图标
                            icon = 'czs-square-l';
                        } else {
                            icon = 'czs-circle-l';
                        }

                        $('#article-index').html($('#article-index').html() + '<li id="ti' + (
                                count_ti++) +
                            '" style="padding-left:' + pf + 'px"><a><i class="' + icon +
                            '"></i>  ' + $(this).eq(
                                0).text().replace(/[ ]/g, "") + '</a></li>'); //创建目录
                        $(this).eq(0).attr('id', 'in' + (count_in++)); //添加id
                        offset[0] = 0;
                        offset[count_ar++] = $(this).eq(0).offset().top; //位置存入数组
                        count_e++;
                        pf = 23; //设置初始偏移值
                        i = 0; //设置循环开始
                    })

                    //跳转对应位置事件
                    $('#article-index li').click(function() {
                        $('html,body').animate({
                            scrollTop: ($('#in' + $(this).eq(0).attr('id').replace('ti',
                                '')).offset().top - 100)
                        }, 500);
                    });

                    if (count_e !== 1) { //若存在标签

                        $(window).scroll(function() { //滑动窗口时
                            var scroH = $(this).scrollTop() + 130;
                            var navH = offset[count_sc]; //从1开始获取当前位置
                            var navH_prev = offset[count_sc - 1]; //获取上一个位置(以备回滑)
                            if (scroH >= navH) { //滑过当前位置
                                $('#ti' + (count_sc - 1)).attr('class', '');
                                $('#ti' + count_sc).attr('class', 'active');
                                count_sc++; //调至下一个位置
                            }
                            if (scroH <= navH_prev) { //滑回上一个h3位置,调至上一个位置
                                $('#ti' + (count_sc - 2)).attr('class', 'active');
                                count_sc--;
                                $('#ti' + count_sc).attr('class', '');
                            }
                        });

                    } else {
                        $('.index-div').css('display', 'none')
                    }
                    /* 文章目录 */

                    //代码高亮
                    document.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightBlock(block);
                    });
                })
        }
    });


});
</script>





















<?php get_footer(); ?>