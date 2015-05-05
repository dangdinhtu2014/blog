<!-- BEGIN: main -->
<h3 class="bl-post-caption">{TABLE_CAPTION}</h3>
<!-- BEGIN: error --><div class="alert alert-danger">{ERROR}</div><!-- END: error -->
<form class="form-inline" method="post" action="{FORM_ACTION}" id="post-form">
	<input type="hidden" name="id" id="post-id" value="{ID}"/>
	<div class="bl-post-col-right clearfix">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogTools}</div>
			<div class="ct">
				<p class="text-center">
					<input type="submit" name="submit" value="{LANG.blogPublic}" class="btn btn-primary bl-button-min" id="button-public"/>
					<input type="submit" name="draft" value="{LANG.blogSaveDraft}" class="btn btn-default bl-button-min" id="button-draft"/>
					<p id="post-message" class="text-center">&nbsp;</p>
				</p>
				<p>{LANG.blogPubtime1}:</p>
				<p>
					<select name="pubtime_h" class="form-control">
						<!-- BEGIN: pubtime_h --><option value="{HOUR.key}"{HOUR.pub}>{HOUR.title}</option><!-- END: pubtime_h -->
					</select> : 
					<select name="pubtime_m" class="form-control">
						<!-- BEGIN: pubtime_m --><option value="{MIN.key}"{MIN.pub}>{MIN.title}</option><!-- END: pubtime_m -->
					</select> {GLANG.day}
					<input type="text" class="form-control bl-col-left-small" value="{DATA.pubtime}" name="pubtime" id="pubtime"/>
				</p>
				<p>{LANG.blogExptime1}:</p>
				<p>
					<select name="exptime_h" class="form-control">
						<!-- BEGIN: exptime_h --><option value="{HOUR.key}"{HOUR.exp}>{HOUR.title}</option><!-- END: exptime_h -->
					</select> : 
					<select name="exptime_m" class="form-control">
						<!-- BEGIN: exptime_m --><option value="{MIN.key}"{MIN.exp}>{MIN.title}</option><!-- END: exptime_m -->
					</select> {GLANG.day}
					<input type="text" class="form-control bl-col-left-small" value="{DATA.exptime}" name="exptime" id="exptime"/>
				</p>
				<p>{LANG.blogExpMode1}:</p>
				<p>
					<select name="expmode" class="form-control bl-txt-f">
						<!-- BEGIN: expmode --><option value="{EXPMODE.key}"{EXPMODE.selected}>{EXPMODE.title}</option><!-- END: expmode -->
					</select>
				</p>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogInCats}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-list-item">
					<!-- BEGIN: cat --><li><label>{CAT.defis}<input type="checkbox" name="catids[]" value="{CAT.id}"{CAT.checked}/> {CAT.title}</label></li><!-- END: cat -->
				</ul>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogTags}</div>
			<div class="ct">
				<input type="hidden" name="tagids" value="{TAGIDS}" id="post-tags"/>
				<ul class="bl-list-tags" id="post-tags-list"> 
					<!-- BEGIN: tag --><li rel="{TAG.id}">{TAG.title}<span>&nbsp;</span></li><!-- END: tag -->
				</ul>
				<!-- BEGIN: mostTags -->
				<div class="bl-hr">&nbsp;</div>
				<p>{LANG.tagsMost}:</p>
				<ul class="bl-list-tags cursor" id="post-tags-most">
					<!-- BEGIN: loop --><li rel="{MOSTTAGS.id}">{MOSTTAGS.title}</li><!-- END: loop -->
				</ul>
				<!-- END: mostTags -->
				<div class="bl-hr">&nbsp;</div>
				<p>{LANG.tagsSearch}:</p>
				<input type="text" class="form-control bl-col-day" id="post-tags-type"/>
				<input type="button" class="btn btn-default" value="{LANG.add}" id="post-tags-button"/>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogposttype}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-list-item">
					<!-- BEGIN: posttype -->
					<li><label><input type="radio" name="posttype" value="{POSTTYPE.key}"{POSTTYPE.checked}/> {POSTTYPE.title}</label></li>
					<!-- END: posttype -->
				</ul>
			</div>
		</div>
		<div class="bl-post-panel collapse">
			<div class="tl">{LANG.blogOtherOption}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-ul">
					<li><label><input type="checkbox" name="newsletters" value="1"{NEWSLETTERS}/> {LANG.blogSendNewsletter}</label></li>
					<li><label><input type="checkbox" name="fullpage" value="1"{FULLPAGE}/> {LANG.blogFullPage}</label></li>
					<li>
						<label>
							{LANG.blogGoogleAuthor}: 
							<input type="text" class="form-control bl-col-day" name="postgoogleid" value="{DATA.postgoogleid}"/>
						</label>
					</li>
					<li><label><input type="checkbox" name="isAutoKeywords" value="1"{ISAUTOKEYWORDS}/> {LANG.blogIsAutoKeywords}</label></li>
					<li><label><input type="checkbox" name="inhome" value="1"{INHOME}/> {LANG.bloginhome}</label></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="bl-post-col-left">
		<dl class="bl-post-dl">
			<dt class="aright"><label for="title">{LANG.blogTitle}:</label></dt>
			<dd>
				<input type="text" class="form-control bl-txt-fh" id="title" name="title" value="{DATA.title}" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias','post');"/>
				<input type="button" class="btn btn-default" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias','post');"/>
			</dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="alias">{LANG.alias}:</label></dt>
			<dd><input type="text" class="form-control bl-txt-f" id="alias" name="alias" value="{DATA.alias}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="sitetitle">{LANG.blogSiteTitle}:</label></dt>
			<dd><input type="text" class="form-control bl-txt-f" id="sitetitle" name="sitetitle" value="{DATA.sitetitle}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="keywords">{LANG.keywords}:</label></dt>
			<dd><input type="text" class="form-control bl-txt-f" id="keywords" name="keywords" value="{DATA.keywords}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="hometext">{LANG.blogHometext}:</label></dt>
			<dd><textarea type="text" class="bl-txt-f autoresize textarea-animated form-control" id="hometext" name="hometext" rows="1">{DATA.hometext}</textarea></dd>
		</dl>
		<p><strong>{LANG.blogBodyhtml}:</strong></p>
		<div class="bl-editor">
			{DATA.bodyhtml}
		</div>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="images">{LANG.blogImages}:</label></dt>
			<dd>
				<input type="text" class="form-control bl-txt-q" name="images" id="images" value="{DATA.images}"/>
				<input type="button" class="btn btn-default" name="imagesBrowser" id="imagesBrowser" value="{LANG.browser}" onclick="nv_open_browse(script_name + '?' + nv_name_variable + '=upload&popup=1&area=images&path={UPLOADS_PATH}&type=image&currentpath={CURRENT_PATH}', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
				<input type="button" class="btn btn-default" name="imagesView" id="imagesView" value="{LANG.view}" onclick="viewImages();"/>
			</dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="mediatype">{LANG.blogmediatype}:</label></dt>
			<dd>
				<select name="mediatype" id="mediatype" class="form-control">
					<!-- BEGIN: mediatype --><option value="{MEDIATYPE.key}"{MEDIATYPE.selected}>{MEDIATYPE.title}</option><!-- END: mediatype -->
				</select>
			</dd>
		</dl>
		<dl class="bl-post-dl" id="mediaheight-wrap">
			<dt class="aright"><label for="mediaheight">{LANG.blogMediaHeight}:</label></dt>
			<dd><input type="text" class="form-control bl-txt-q" name="mediaheight" id="mediaheight" value="{DATA.mediaheight}"/></dd>
		</dl>
		<dl class="bl-post-dl" id="mediavalue-wrap">
			<dt class="aright"><label for="mediavalue">{LANG.blogMediaValue}:</label></dt>
			<dd>
				<input type="text" class="form-control bl-txt-q" name="mediavalue" id="mediavalue" value="{DATA.mediavalue}"/>
				<input type="button" class="btn btn-default" name="mediaBrowser" id="mediaBrowser" value="{LANG.browser}" onclick="nv_open_browse(script_name + '?' + nv_name_variable + '=upload&popup=1&area=mediavalue&path={UPLOADS_PATH}&type=file&currentpath={CURRENT_PATH}', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
			</dd>
		</dl>
	</div>
	<div style="display:block;clear: both;">&nbsp;</div>
</form>
<script type="text/javascript">
function mediaHandle(){
	var mediatype = $('#mediatype').val();
	if( mediatype != '2' && mediatype != '3' &&  mediatype != 4 ){
		$('#mediaheight').attr('disabled','disabled');
		$('#mediaheight-wrap').hide();
	}
	else
	{
		$('#mediaheight').removeAttr('disabled');
		$('#mediaheight-wrap').show();
	}
	if( mediatype == '0' ){
		$('#mediavalue').attr('disabled','disabled');
		$('#mediavalue-wrap').hide();
	}
	else
	{
		$('#mediavalue').removeAttr('disabled');
		$('#mediavalue-wrap').show();
	}
	if( mediatype == '0' || mediatype == '4' ){
		$('#mediaBrowser').hide();
	}else{
		$('#mediaBrowser').show();
	}
}
function viewImages(){
	var images = $('#images').val();
	if( images == '' ){
		return;
	}
	Shadowbox.open({
		content : images,
		player : 'img',
		hanleOversize: 'resize'
	});
}
$(document).ready(function(){
	BL.tags.init();
	BL.post.init({EDITOR});
	mediaHandle();
	// Xu ly panel
	$('.bl-post-panel .tl').click(function(){
		var $this = $(this).parent();
		
		if( $this.hasClass('collapse') ){
			$this.find('.ct').slideDown(200, function(){$this.removeClass('collapse').addClass('decollapse')});
		}else{
			$this.find('.ct').slideUp(200, function(){$this.removeClass('decollapse').addClass('collapse')});
		}
	});
	
	// Chon ngay thang
	$("#pubtime, #exptime").datepicker({
		dateFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		showButtonPanel: true,
		showOn: 'focus'
	});
	
	// Xu ly media
	$('#mediatype').change(function(){
		mediaHandle();
	});
});
</script>
<div class="clear"></div>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="alert alert-success center">
	<p>{MESSAGE}</p>
	<p><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<!-- END: complete -->