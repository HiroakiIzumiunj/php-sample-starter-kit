window.addEventListener('DOMContentLoaded',()=>{

    //「送信」ボタンの要素を取得
    const submit = document.querySelector('#submit');

    //「送信」ボタンの要素にクリックイベントを設定する
    submit.addEventListener('click', (e) => {

        //ユーザー名フォームの要素を取得
        const username = document.querySelector('#username');
        console.log(username);

        //ユーザー名エラーメッセージを表示させる要素を取得
        const errMsgName = document.querySelector('#err-msg-name');

        //コメントフォームの要素を取得
        const comment = document.querySelector('#comment');
        console.log(comment);

        //コメントエラーメッセージを表示させる要素を取得
        const errMsgComment = document.querySelector('#err-msg-comment');

        //「氏名」入力欄の文字数が0ならば
        if(!username.value){

            //クラスを追加（エラーメッセージを表示する）
            errMsgName.classList.add("invalid-feedback");

            // エラーメッセージのテキスト
            errMsgName.textContent = 'ユーザー名は必須です!!!!。';

            //クラスを追加（フォームの枠線を赤くする）
            username.classList.add('is-invalid');

            //デフォルトアクションをキャンセル
            e.preventDefault();

        //「氏名」入力欄の文字数が20以上ならば
        }else if(username.value.length > 20){

            //クラスを追加（エラーメッセージを表示する）
            errMsgName.classList.add('invalid-feedback');

            // エラーメッセージのテキスト
            errMsgName.textContent = 'ユーザー名を20文字以内で入力して下さい!!!!。';

            //クラスを追加（フォームの枠線を赤くする）
            username.classList.add('is-invalid');

            //デフォルトアクションをキャンセル
            e.preventDefault();

        
        }else{

            //ユーザー名エラー表示のクラスを削除
            username.classList.remove('invalid-feedback');
            username.classList.remove('is-invalid');
        }
        
        //「氏名」入力欄の文字数が100以上ならば
        if(comment.value.length > 100){

            //クラスを追加（エラーメッセージを表示する）
            errMsgComment.classList.add('invalid-feedback');

            // エラーメッセージのテキスト
            errMsgComment.textContent = 'ユーザーは100文字以内で入力して下さい!!!!!。';

            //クラスを追加（フォームの枠線を赤くする）
            comment.classList.add('is-invalid');

            //デフォルトアクションをキャンセル
            e.preventDefault();

        }
        else{ 
            //コメントエラー表示のクラスを削除
            comment.classList.remove('input-invalid');
            comment.classList.remove('is-invalid');
        }
    });
});