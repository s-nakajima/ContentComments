# ContentComments
ContentComments for NetCommons3

[![Build Status](https://api.travis-ci.org/NetCommons3/ContentComments.svg?branch=master)](https://travis-ci.org/NetCommons3/ContentComments)
[![Coverage Status](https://img.shields.io/coveralls/NetCommons3/ContentComments.svg)](https://coveralls.io/r/NetCommons3/ContentComments?branch=master)

| dependencies | status |
| ------------ | ------ |
| composer.json | [![Dependency Status](https://www.versioneye.com/user/projects/5539e4a10b24229e14000002/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5539e4a10b24229e14000002) |

### 概要
コンテンツの一覧にコメント数を表示する機能と、コンテンツの詳細でコメントを投稿する機能を提供します。<br>
利用するプラグインはコメントの使用有無(use_comment)、コメントの承認有無(use_comment_approval)を定義してください。<br>
<br>

#### コンテンツの一覧にコメント数を表示
ContentCommentBehaviorとContentCommentHelperを使用します。<br>
コメントと紐づくモデルにContentCommentBehavior、<br>
コンテンツ一覧のコントローラーにContentCommentHelperを定義してください。

##### サンプルコード
###### コントローラー
```php
class VideosController extends VideosAppController {

	public $uses = array(
		'Videos.Video',
		'Videos.VideoBlockSetting'
	);

	public $helpers = array(
		'ContentComments.ContentComment' => array(
			'viewVarsKey' => array(
				'contentKey' => 'video.Video.key',
				'useComment' => 'videoBlockSetting.use_comment',
				'useCommentApproval' => 'videoBlockSetting.use_comment_approval'
			)
		)
	);

	public function index() {
		$query = array(
			'conditions' => array(
				'VideoBlockSetting.block_key' => Current::read('Block.key')
			)
		);
		$viewVars['videoBlockSetting'] = $this->VideoBlockSetting->find('first', $query);
		$viewVars['videos'] = $this->Video->find('all');

		$this->set($viewVars);
	}
}
```

###### モデル
```php
class Video extends VideoAppModel {
	public $actsAs = array(
		'ContentComments.ContentComment'
	);
}
```

###### ビュー（ctpテンプレート）
```php
<?php
	foreach ($videos as $video) {
		echo $video['Video']['title'];
		echo $this->ContentComment->count($video);
	}
?>
```

<!--
##### [ContentCommentBehavior](https://github.com/NetCommons3/NetCommons3Docs/blob/master/phpdocMd/AuthorizationKeys/AuthorizationKeyComponent.md#authorizationkeycomponent)
##### [ContentCommentHelper](https://github.com/NetCommons3/NetCommons3Docs/blob/master/phpdocMd/AuthorizationKeys/AuthorizationKeyComponent.md#authorizationkeycomponent)
 -->
<br>

#### コンテンツの詳細でコメントを投稿する
ContentCommentsComponentとContentCommentHelperを使用します。<br>
コンテンツ詳細のコントローラーにContentCommentsComponentを定義してください。

##### サンプルコード
###### コントローラー
```php
class VideosController extends VideosAppController {

	public $uses = array(
		'Videos.Video',
		'Videos.VideoBlockSetting'
	);

	public $components = array(
		'ContentComments.ContentComments' => array(
			'viewVarsKey' => array(
				'contentKey' => 'video.Video.key',
				'useComment' => 'videoBlockSetting.use_comment'
			),
			'allow' => array('view')
		)
	)

	public function view($videoKey) {
		$query = array(
			'conditions' => array(
				'VideoBlockSetting.block_key' => Current::read('Block.key')
			)
		);
		$viewVars['videoBlockSetting'] = $this->VideoBlockSetting->find('first', $query);

		$query = array(
			'conditions' => array(
				'Video.key' => $videoKey,
				'Video.language_id' => Current::read('Language.id')
			)
		);
		$viewVars['video'] = $this->Video->find('first', $query);

		$this->set($viewVars);
	}
}
```

###### ビュー（ctpテンプレート）
```
<?php
	echo $video['title'];
	echo $this->ContentComment->index($video);
?>
```

<!--
##### [ContentCommentsComponent](https://github.com/NetCommons3/NetCommons3Docs/blob/master/phpdocMd/AuthorizationKeys/AuthorizationKeyComponent.md#authorizationkeycomponent)
##### [ContentCommentHelper](https://github.com/NetCommons3/NetCommons3Docs/blob/master/phpdocMd/AuthorizationKeys/AuthorizationKeyComponent.md#authorizationkeycomponent)
 -->
