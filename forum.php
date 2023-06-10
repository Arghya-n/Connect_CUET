<?php
include 'admin/db_connect.php';
?>

<header class="masthead">
    <div class="container h-150">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-4 align-self-end mb-4" style="background: #0000002e;">
                <h3 class="text-white ">Forum List</h3>
            </div>
            <div class="row col-md-12 mb-2 justify-content-center">
                <button class="btn btn-primary btn-block col-sm-4" type="button" id="new_forum"><i
                        class="fa fa-plus"></i> Create New Topic</button>
            </div>
        </div>
    </div>
</header>

<div class="container pt-5">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="filter-field"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="filter" placeholder="Filter" aria-label="Filter"
                            aria-describedby="filter-field">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-block btn-sm" id="search">Search</button>
                </div>
            </div>

        </div>
    </div>
    <?php
    $event = $conn->query("SELECT f.*,u.name from forum_topics f inner join users u on u.id = f.user_id order by f.id desc");
    while ($row = $event->fetch_assoc()):
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
        $desc = strtr(html_entity_decode($row['description']), $trans);
        $desc = str_replace(array("<li>", "</li>"), array("", ","), $desc);
        $count_comments = 0;
        $count_comments = $conn->query("SELECT * FROM forum_comments where topic_id = " . $row['id'])->num_rows;
        ?>
        <div class="card Forum-list" data-id="<?php echo $row['id'] ?>">
            <div class="card-body">
                <div class="row  align-items-center justify-content-center text-center h-100">
                    <div class="">
                        <h3><b class="filter-txt">
                                <?php echo ucwords($row['title']) ?>
                            </b></h3>
                        <hr>
                        <larger class="truncate filter-txt">
                            <?php echo strip_tags($desc) ?>
                        </larger>
                        <br>
                        <hr class="divider" style="max-width: calc(80%)">
                        <span class="badge badge-info float-left px-3 pt-1 pb-1">
                            <b><i>Topic Created by: <span class="filter-txt">
                                        <?php echo $row['name'] ?>
                                    </span></i></b>
                        </span>
                        <span class="badge badge-secondary float-left px-3 pt-1 pb-1 ml-2">
                            <b><i class="fa fa-comments"></i> <i>
                                    <?php echo $count_comments ?> Comments
                                </i></b>
                        </span>
                        <button class="btn btn-primary float-right view_topic" data-id="<?php echo $row['id'] ?>">View
                            Topic</button>
                    </div>
                </div>


            </div>
        </div>
        <br>
    <?php endwhile; ?>

</div>

</div>


<script>
    // $('.card.gallery-list').click(function(){
    //     location.href = "index.php?page=view_gallery&id="+$(this).attr('data-id')
    // })

    $('#filter').keypress(function (e) {
        if (e.which == 13)
            $('#search').trigger('click')
    })
    $('#search').click(function () {
        var txt = $('#filter').val()
        start_load()
        if (txt == '') {
            $('.Forum-list').show()
            end_load()
            return false;
        }
        $('.Forum-list').each(function () {
            var content = "";
            $(this).find(".filter-txt").each(function () {
                content += ' ' + $(this).text()
            })
            if ((content.toLowerCase()).includes(txt.toLowerCase()) == true) {
                $(this).toggle(true)
            } else {
                $(this).toggle(false)
            }
        })
        end_load()
    })

</script>