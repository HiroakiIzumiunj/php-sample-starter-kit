window.addEventListener('DOMContentLoaded',()=>{

    
    //「送信」ボタンの要素を取得
    const submit = document.querySelector('#submit');

    //「送信」ボタンの要素にクリックイベントを設定する
    submit.addEventListener('click', (e) => {


        //デフォルトアクションをキャンセル
        e.preventDefault();

        //「氏名」入力欄の空欄チェック
        //フォームの要素を取得
        const username = document.querySelector('#username');

        //エラーメッセージを表示させる要素を取得
        const errMsgName = document.querySelector('.err-msg-name');

        if(!username.value){

            //クラスを追加（エラーメッセージを表示する）invalid-feedback?
            //errMsgName.classList.add('invalid-feedback');

            // エラーメッセージのテキスト
            errMsgName.textContent = 'ユーザー名は必須です。!!!!!';

            //クラスを追加（フォームの枠線を赤くする）is-invalid?
            username.classList.add('is-invalid');

            //後続の処理を止める
            //return;

        }else if(username.string.length > 20){

            //クラスを追加（エラーメッセージを表示する）invalid-feedback?
            errMsgName.classList.add('form-invalid');

            // エラーメッセージのテキスト
            errMsgName.textContent = 'ユーザー名を20文字以内で入力して下さい!!!!。';

            //クラスを追加（フォームの枠線を赤くする）is-invalid?
            username.classList.add('input-invalid');

            //後続の処理を止める
            //return;

        }
        else{
            //エラーメッセージのテキストに空文字を代入
            errMsgName.textContent ='';
            //クラスを削除 is-invalid?
            username.classList.remove('input-invalid');
        }

        //「コメント」入力欄の文字数チェック
        //フォームの要素を取得
        const comment = document.querySelector('#comment');

        //エラーメッセージを表示させる要素を取得
        const errMsgComment = document.querySelector('.err-msg-comment');

        if(comment.string.length > 100){

            //クラスを追加（エラーメッセージを表示する）invalid-feedback?
            errMsgComment.classList.add('form-invalid');

            // エラーメッセージのテキスト
            errMsgName.textContent = 'ユーザーは100文字以内で入力して下さい。';

            //クラスを追加（フォームの枠線を赤くする）is-invalid?
            comment.classList.add('input-invalid');

            //後続の処理を止める
            return;

        }else{
            //エラーメッセージのテキストに空文字を代入
            errMsgName.textContent ='';
            //クラスを削除 is-invalid?
            comment.classList.remove('input-invalid');
        }
    });
});