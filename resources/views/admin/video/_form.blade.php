<div class="form-group">
    <label for="tag" class="col-md-3 control-label">视频名称</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="title" id="tag" value="{{ $title }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">优酷ID</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="youku_id" id="tag" value="{{ $youku_id }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">阿里云ID</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="aliyun_id" id="tag" value="{{ $aliyun_id }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">等级</label>
    <div class="col-md-5">
        <select name="level" id="tag" class="form-control" autofocus>
            <?php
            foreach (['一直使用阿里云','一个月内使用','三个月内使用','一年内使用','永远不使用'] as $key=>$val){
                $value = $key + 1;
                if($value==$level){
                    echo "<option value={$value} selected>$val</option>";
                }else{
                    echo "<option value={$value}>$val</option>";
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">当前视频源</label>
    <div class="col-md-5">
        <select name="current_type" id="tag" class="form-control" autofocus>
            <?php
            foreach (['阿里云','优酷'] as $key=>$val){
                $value = $key + 1;
                if($value==$current_type){
                    echo "<option value={$value} selected>$val</option>";
                }else{
                    echo "<option value={$value}>$val</option>";
                }
            }
            ?>
        </select>
    </div>
</div>


