<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Language Tiếng Việt
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '07/03/2011, 20:15';
$lang_translator['copyright'] = '@Copyright (C) 2011 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['isDevelop'] = 'Chức năng đang trong quá trình phát triển';

$lang_module['title'] = 'Tiêu đề';
$lang_module['alias'] = 'Liên kết tĩnh';
$lang_module['aliasAutoGet'] = 'Tạo LKT';
$lang_module['save'] = 'Lưu lại';
$lang_module['keywords'] = 'Từ khóa cho máy tìm kiếm';
$lang_module['keywordsNote'] = 'Phân cách bằng dấu &quot;,&quot;';
$lang_module['description'] = 'Mô tả cho máy tìm kiếm';
$lang_module['descriptionNote'] = 'Không được quá ngắn, không bỏ trống';
$lang_module['errorKeywords'] = 'Lỗi: Từ khóa còn trống';
$lang_module['errorSescription'] = 'Lỗi: Mô tả còn trống';
$lang_module['errorAliasExists'] = 'Lỗi: Liên kết tĩnh này đã tồn tại, vui lòng chọn liên kết tĩnh khác';
$lang_module['errorSaveUnknow'] = 'Lỗi lưu dữ liệu không xác định';
$lang_module['errorUpdateUnknow'] = 'Lỗi cập nhật dữ liệu không xác định';
$lang_module['order'] = 'Thứ tự';
$lang_module['feature'] = 'Chức năng';
$lang_module['status'] = 'Hoạt động';
$lang_module['status1'] = 'Trạng thái';
$lang_module['alert_check'] = 'Bạn phải chọn ít nhất một dòng để thao tác';
$lang_module['numposts'] = 'Số bài viết';
$lang_module['add'] = 'Thêm';
$lang_module['browser'] = 'Duyệt';
$lang_module['view'] = 'Xem';

$lang_module['mainTitle'] = 'Hệ thống quản lý blog';
$lang_module['mainNotice'] = 'Các cảnh báo cần giải quyết';
$lang_module['mainNoticeEmpty'] = 'Tuyệt! Không có cảnh báo nào';
$lang_module['mainTagsWarning'] = '%d tags chưa được cập nhật từ khóa hoặc mô tả';
$lang_module['mainPostExpriedWarning'] = '%d bài viết hết hạn';
$lang_module['mainPostDraftWarning'] = '%d bài viết nháp';
$lang_module['mainPostWaitWarning'] = '%d bài viết đang chờ đăng';
$lang_module['mainStat'] = 'Số liệu thống kê';
$lang_module['mainStatPostTotal'] = 'Tổng số bài viết hiện có: <strong>%s</strong>';
$lang_module['mainStatTagsTotal'] = 'Tổng số tags hiện có: <strong>%s</strong>';
$lang_module['mainStatNewsletters'] = 'Tổng số email đăng ký nhận tin hiện tại: <strong>%s</strong>';
$lang_module['mainInfo'] = 'Thông tin module';
$lang_module['mainInfoVersion'] = 'Số phiên bản';
$lang_module['mainInfoRelease'] = 'Ngày phát hành';
$lang_module['mainInfoAuthor'] = 'Tác giả';
$lang_module['mainInfoContact'] = 'Liên hệ';
$lang_module['mainInfoSupport'] = 'Hỗ trợ';
$lang_module['mainInfoIssue'] = 'Báo lỗi';
$lang_module['mainDonation'] = 'Tài trợ';
$lang_module['mainQuickLink'] = 'Liên kết hữu dụng';
$lang_module['mainCfgFbAppIDWarning'] = 'Giá trị Facebook App ID chưa được đặt, chức năng like facebook sẽ không hiển thị';
$lang_module['mainCfgGoogleAuthorWarning'] = 'Giá trị Google Author mặc định chưa được đặt';

$lang_module['blogList'] = 'Danh sách bài viết';
$lang_module['blogAdd'] = 'Viết bài';
$lang_module['blogEdit'] = 'Sửa bài viết';
$lang_module['blogManager'] = 'Quản lý bài viết';
$lang_module['blogTitle'] = 'Tên bài viết';
$lang_module['blogSiteTitle'] = 'Tiêu đề trang';
$lang_module['blogSiteTitleNote'] = 'Mặc định là tên bài viết';
$lang_module['blogBodyhtml'] = 'Nội dung bài viết';
$lang_module['blogTools'] = 'Công cụ';
$lang_module['blogInCats'] = 'Danh mục bài viết';
$lang_module['blogTags'] = 'Tags';
$lang_module['blogImages'] = 'Ảnh bài viết';
$lang_module['blogposttime'] = 'Thời gian đăng';
$lang_module['blogupdatetime'] = 'Thời gian cập nhật';
$lang_module['blogposttype'] = 'Loại bài viết';
$lang_module['blogposttype0'] = 'Bài viết bình thường';
$lang_module['blogposttype1'] = 'Bài viết có hình ảnh';
$lang_module['blogposttype2'] = 'Bài viết có video';
$lang_module['blogposttype3'] = 'Bài viết có âm thanh';
$lang_module['blogposttype4'] = 'Bài ghi chú';
$lang_module['blogposttype5'] = 'Bài viết với liên kết';
$lang_module['blogposttype6'] = 'Thư viện';
$lang_module['blogmediatype'] = 'Loại Media';
$lang_module['blogmediatype0'] = 'Ảnh đại diện';
$lang_module['blogmediatype1'] = 'Ảnh tùy chọn';
$lang_module['blogmediatype2'] = 'Âm thanh';
$lang_module['blogmediatype3'] = 'Video';
$lang_module['blogmediatype4'] = 'Iframe';
$lang_module['blogMediaHeight'] = 'Chiều cao của media';
$lang_module['blogMediaValue'] = 'Giá trị của media';
$lang_module['blogHometext'] = 'Nội dung tóm tắt';
$lang_module['blogPublic'] = 'Đăng bài viết';
$lang_module['blogSaveDraft'] = 'Lưu bản nháp';
$lang_module['blogPubtime'] = 'Ngày xuất bản';
$lang_module['blogPubtime1'] = 'Xuất bản bài viết vào lúc';
$lang_module['blogExptime1'] = 'Hết hạn vào lúc';
$lang_module['blogExpMode1'] = 'Khi hết hạn sẽ';
$lang_module['blogExpMode_0'] = 'Ngưng hoạt động';
$lang_module['blogExpMode_1'] = 'Cho thành hết hạn';
$lang_module['blogExpMode_2'] = 'Xóa vĩnh viễn bài viết';
$lang_module['blogErrorTitle'] = 'Lỗi: Chưa nhập tên bài viết';
$lang_module['blogErrorHometext'] = 'Lỗi: Nội dung tóm tắt còn trống';
$lang_module['blogErrorBodyhtml'] = 'Lỗi: Nội dung bài viết còn trống';
$lang_module['blogErrorCategories'] = 'Lỗi: Chưa chọn danh mục cho bài viết';
$lang_module['blogErrorExpThanPub'] = 'Lỗi: Thời gian xuất bản bài viết phải sớm hơn thời gian hết hạn bài viết';
$lang_module['blogErrorExp'] = 'Lỗi: Bạn đã chọn phương thức xử lý là xóa bài viết khi hết hạn, nhưng bài viết đang soạn thảo đã là hết hạn nên bạn không thể lưu được';
$lang_module['blogErrorCreatTable'] = 'Lỗi: Hệ thống không thể tạo thêm bảng dữ liệu nên không thể thực hiện yêu cầu này';
$lang_module['blogErrorSaveHtml'] = 'Lỗi: Hệ thống không lưu được nội dung bài viết';
$lang_module['blogErrorUpdateHtml'] = 'Lỗi: Hệ thống không cập nhật được nội dung bài viết';
$lang_module['blogErrorMediaValue'] = 'Lỗi: Chưa nhập nội dung media';
$lang_module['blogErrorMediaHeight'] = 'Lỗi: Chưa nhập nội chiều cao media';
$lang_module['blogSaveDraftOk'] = 'Lưu bản nháp thành công';
$lang_module['blogSaveOk'] = 'Lưu dữ liệu thành công, hệ thống sẽ tự động chuyển trang trong giây lát';
$lang_module['blogStatus-2'] = 'Bản nháp';
$lang_module['blogStatus-1'] = 'Chờ đăng';
$lang_module['blogStatus0'] = 'Tạm ngưng';
$lang_module['blogStatus1'] = 'Hiệu lực';
$lang_module['blogStatus2'] = 'Hết hạn';
$lang_module['blogDelete'] = 'Xóa bài viết';
$lang_module['blogOtherOption'] = 'Các tùy chọn khác';
$lang_module['blogSendNewsletter'] = 'Gửi email đến các email đăng ký nhận tin';
$lang_module['blogFullPage'] = 'Hiển thị toàn bộ bài viết tại trang danh sách nếu kiểu hiển thị là blog';
$lang_module['blogGoogleAuthor'] = 'Google Author';
$lang_module['blogIsAutoKeywords'] = 'Tự động xác định từ khóa nếu không nhập';
$lang_module['bloginhome'] = 'Hiển thị tại trang chủ';

$lang_module['categoriesManager'] = 'Quản lý danh mục bài viết';
$lang_module['categoriesEmpty'] = 'Chưa có danh mục bài viết nào, hãy thêm danh mục từ trình đơn bên dưới';
$lang_module['categoriesAdd'] = 'Thêm danh mục bài viết';
$lang_module['categoriesEdit'] = 'Sửa danh mục &quot;%s&quot;';
$lang_module['categoriesEditLog'] = 'Sửa danh mục';
$lang_module['categoriesInCat'] = 'Thuộc danh mục';
$lang_module['categoriesErrorTitle'] = 'Lỗi: Chưa nhập tiêu đề danh mục';
$lang_module['categoriesnumPost'] = 'Số bài đăng';
$lang_module['categoriesMainCat'] = 'Là danh mục chính';
$lang_module['categoriesListMainCat'] = 'Các danh mục chính';
$lang_module['categoriesHasSub'] = '<strong>%d</strong> danh mục con';
$lang_module['categoriesDelete'] = 'Xóa danh mục';

$lang_module['nltList'] = 'Danh sách đăng ký nhận tin';
$lang_module['nltEmail'] = 'Email nhận tin';
$lang_module['nltregtime'] = 'Thời gian đăng ký';
$lang_module['nltconfirmtime'] = 'Thời gian kích hoạt';
$lang_module['nltlastsendtime'] = 'Lần gửi cuối';
$lang_module['nltnumemail'] = 'Số email đã gửi';
$lang_module['nltregip'] = 'IP đăng ký';
$lang_module['nltstatus-1'] = 'Chưa xác nhận';
$lang_module['nltstatus0'] = 'Ngừng nhận email';
$lang_module['nltstatus1'] = 'Đang nhận email';
$lang_module['nltDelete'] = 'Xóa email nhận tin';

$lang_module['filter_from'] = 'từ';
$lang_module['filter_to'] = 'đến';
$lang_module['filter_action'] = 'Tìm kiếm';
$lang_module['filter_cancel'] = 'Hủy';
$lang_module['filter_clear'] = 'Xóa';
$lang_module['filter_err_submit'] = 'Bạn cần chọn ít nhất một điều kiện để lọc dữ liệu';
$lang_module['filter_lang_asc'] = 'tăng dần';
$lang_module['filter_lang_desc'] = 'giảm dần';
$lang_module['filter_order_by'] = 'Sắp xếp theo %s thứ tự';
$lang_module['filter_all_cat'] = 'Tất cả chuyên mục';
$lang_module['filter_all_status'] = 'Tất cả trạng thái';

$lang_module['action_status_ok'] = 'Cho hoạt động';
$lang_module['action_status_no'] = 'Cho ngưng hoạt động';
$lang_module['action_status_public'] = 'Cho đăng bài viết';

$lang_module['searchEmail'] = 'Tìm email';
$lang_module['searchTags'] = 'Tìm tags';
$lang_module['searchPost'] = 'Tìm bài viết';

$lang_module['cfgMaster'] = 'Thiết lập module';
$lang_module['cfgView'] = 'Cấu hình hiển thị';
$lang_module['cfgindexViewType'] = 'Kiểu hiển thị tại trang chủ';
$lang_module['cfgindexViewType_type_blog'] = 'Dạng các bài blog (Ảnh, video demo + nội dung)';
$lang_module['cfgindexViewType_type_news'] = 'Dạng tin tức theo danh sách';
$lang_module['cfgcatViewType'] = 'Kiểu hiển thị tại trang danh mục';
$lang_module['cfgcatViewType_type_blog'] = 'Dạng các bài blog (Ảnh, video demo + nội dung)';
$lang_module['cfgcatViewType_type_news'] = 'Dạng tin tức theo danh sách';
$lang_module['cfgtagsViewType'] = 'Kiểu hiển thị tại trang tags';
$lang_module['cfgtagsViewType_type_blog'] = 'Dạng các bài blog (Ảnh, video demo + nội dung)';
$lang_module['cfgtagsViewType_type_news'] = 'Dạng tin tức theo danh sách';
$lang_module['cfgPost'] = 'Thiết lập đăng bài';
$lang_module['cfginitPostExp'] = 'Kiểu xử lý mặc định khi hết hạn bài viết lúc đăng bài';
$lang_module['cfginitPostType'] = 'Kiểu bài viết mặc định';
$lang_module['cfginitMediaType'] = 'Loại Media mặc định';
$lang_module['cfginitMediaHeight'] = 'Chiều cao Media mặc định';
$lang_module['cfginitNewsletters'] = 'Gửi mail đến các email đăng ký nhận bản tin khi tạo bài viết';
$lang_module['cfgfolderStructure'] = 'Cấu trúc thư mục chứa media và ảnh';
$lang_module['cfgNewsletter'] = 'Cấu hình gửi mail';
$lang_module['cfgnumberResendNewsletter'] = 'Số lần gửi lại email';
$lang_module['cfgnumPostPerPage'] = 'Số bài đăng trên một trang';
$lang_module['cfgstrCutHomeText'] = 'Cắt phần mô tả ngắn gọn tại trang danh sách bài viết. Nhập 0 nếu không muốn cắt';
$lang_module['cfgTheme'] = 'Cấu hình giao diện';
$lang_module['cfgIconPost'] = 'Tên class css của loại %s';
$lang_module['cfgBlockTags'] = 'Cấu hình block tags';
$lang_module['cfgblockTagsShowType'] = 'Kiểu hiển thị';
$lang_module['cfgblockTagsShowType_random'] = 'Hiển thị ngẫu nhiên';
$lang_module['cfgblockTagsShowType_latest'] = 'Mới nhất lên trên';
$lang_module['cfgblockTagsShowType_popular'] = 'Có nhiều bài viết nhất lên trên';
$lang_module['cfgblockTagsNums'] = 'Số tags hiển thị';
$lang_module['cfgblockTagsCacheIfRandom'] = 'Lưu cache nếu kiểu hiển thị random';
$lang_module['cfgblockTagsCacheLive'] = 'Thời gian tồn tại của cache';
$lang_module['cfgSys'] = 'Tùy chỉnh chuyên sâu';
$lang_module['cfgsysDismissAdminCache'] = 'Không lưu cache đối với admin';
$lang_module['cfgsysHighlightTheme'] = 'Giao diện định dạng code';
$lang_module['cfgnumSearchResult'] = 'Số kết quả tìm kiếm trên một trang';
$lang_module['cfgsysGoogleAuthor'] = 'Tác giả google + mặc định khi tạo bài đăng';
$lang_module['cfgsysRedirect2Home'] = 'Chuyển hướng về trang chủ nếu đường dẫn là main của module (Chọn nếu module được chọn làm trang chủ)';
$lang_module['cfgStructureData'] = 'Cấu hình dữ liệu có cấu trúc';
$lang_module['cfgsysFbAppID'] = 'Facebook APP ID';
$lang_module['cfgsysFbAdminID'] = 'Facebook Admin ID';
$lang_module['cfgsysLocale'] = 'Ngôn ngữ và quốc gia';
$lang_module['cfgsysDefaultImage'] = 'Hình ảnh mặc định cho OpenGraph hoặc Rich Snippets. Nếu không chọn hệ thống sẽ sử dụng logo của website';
$lang_module['cfgComment'] = 'Thiết lập bình luận';
$lang_module['cfgCommentNote'] = 'Chỉ có bình luận của hệ thống là được lưu trữ, các bình luận kiểu khác không được lưu trữ trên máy chủ website. Các kiểu bình luận là độc lập với nhau và tại mỗi một thời điểm sẽ chỉ hiển thị được một loại bình luận nhất định';
$lang_module['cfgCommentType'] = 'Chọn loại bình luận';
$lang_module['cfgCommentType_none'] = 'Không sử dụng bình luận';
$lang_module['cfgCommentType_sys'] = 'Bình luận của hệ thống';
$lang_module['cfgCommentType_facebook'] = 'Facebook Comment';
$lang_module['cfgCommentType_disqus'] = 'Disqus Comment';
$lang_module['cfgCommentPerPage'] = 'Số bình luận trên một trang';
$lang_module['cfgcommentDisqusShortname'] = 'Tài khoản bình luận Disqus';
$lang_module['cfgcommentFacebookColorscheme'] = 'Giao diện bình luận';
$lang_module['cfginitAutoKeywords'] = 'Tự động tạo từ khóa lúc đăng bài nếu không nhập';

$lang_module['tagsMg'] = 'Quản lý tags';
$lang_module['tagsDelete'] = 'Xóa tags';
$lang_module['tagsErrorTitle'] = 'Lỗi: Tên tags không được để trống';
$lang_module['tagsEditLog'] = 'Sửa tags';
$lang_module['tagsAdd'] = 'Thêm tags';
$lang_module['tagsEdit'] = 'Sửa tags';
$lang_module['tagsList'] = 'Danh sách tags';
$lang_module['tagsMost'] = 'Chọn từ tags nổi bật';
$lang_module['tagsSearch'] = 'Tìm hoặc thêm tags';

$lang_module['donateComment'] = 'Quyên góp xây dựng module blog';