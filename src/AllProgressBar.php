<?php

/**
 * Created by PhpStorm.
 * User: chell_uoxou
 * Date: 2016/12/31
 * Time: 午前 12:56
 */
class AllProgressBar extends PrimeNumber
{
    /**
     * プログレスバーを生成する関数を生成します。
     *
     * usage:
     * $all_count = count($array);
     * $progress create_progress($all_count);
     * foreach ($array as $row) {
     *     // $rowを使った何らかの処理
     *     $progress();
     * }
     *
     * 引数無しで$progress();を利用し、進捗が100%まで到達した場合、内部で初期化が入る為、そのまま再利用できます。
     *
     * @param   int $all_count 対象とする処理の全数
     * @param   array $progress_chars プログレスバーでバーとして利用する文字設定
     *  [
     *      'finished'      => string 終了済みを示す文字 キー名を省略した場合、0番目の要素を使う
     *      'current'       => string 現在位置を示す文字 キー名を省略した場合、1番目の要素を使う
     *      'unfinished'    => string 完了済みを示す文字 キー名を省略した場合、2番目の要素を使う
     *  ]
     * @param   string $format プログレスバー表示フォーマット sprintf互換
     *  位置指定子に対する内容
     *      1$：現在の進捗率
     *      2$：プログレスバーの終了分
     *      3$：プログレスバーの現在位置の文字
     *      4$：プログレスバーの未了分
     *      5$：予想残り時間
     *      6$：全数に対する作業完了数
     * @return  callable    プログレスバーを表示する関数
     * @param   int $current 対象とする処理の現在位置 開始値は1 省略すると現在の値に+1した値で実行する
     * @throws     \Exception      $currentが1未満の数字を与えると例外が発生する
     */

    public function create_progress($all_count, $progress_chars = [], $format = null)
    {
        //======================================================
        //関数作成前の初期化
        //======================================================
        //プログレスバー文字の確定
        $finished_str = isset($progress_chars['finished']) ? $progress_chars['finished'] : (isset($progress_chars[0]) ? $progress_chars[0] : '|');
        $current_str = isset($progress_chars['current']) ? $progress_chars['current'] : (isset($progress_chars[1]) ? $progress_chars[1] : '|');
        $unfinished_str = isset($progress_chars['unfinished']) ? $progress_chars['unfinished'] : (isset($progress_chars[2]) ? $progress_chars[2] : ' ');

        //プログレスバーフォーマットの確定
        if ($format === null) {
            $format = sprintf(' %% 3s%%%% [%%s%%s%%s] ETA %%s %% %ss/%s', strlen($all_count), $all_count);
        }

        //======================================================
        //関数構築
        //======================================================
        return function ($current = null) use ($all_count, $finished_str, $current_str, $unfinished_str, $format) {
            //======================================================
            //初期処理
            //======================================================
            //プログレスバー実行開始時点の時間保持用変数
            static $start_ts;

            //直前に表示されたプログレスバーの文字列長
            static $before_width;

            //最後に実行した位置
            static $position;

            //現在の時間を取得
            $current_ts = microtime(true);

            //======================================================
            //検証
            //======================================================
            if ($current !== null && $current < 1) {
                throw new \Exception(sprintf('現在位置は1以上の数値のみ使用できます。$current:%s', $current));
            }

            //======================================================
            //クロージャ—変数の初期化
            //======================================================
            if ($current === null) {
                if ($position === null) {
                    $position = 0;
                }
                $position++;
            } else {
                $position = $current;
            }

            //プログレスバー初期化処理
            if ($position === 1) {
                $start_ts = $current_ts;
            }

            //======================================================
            //実処理
            //======================================================
            //プログレスバー実行開始からの経過時間
            $elapsed_ts = ($current_ts - $start_ts) / $position * ($all_count - $position);

            //予想終了時間の算出
            $eta = sprintf('%02.2s:%02s:%02s.%0-3.3s', $elapsed_ts / 60 / 60 % 60, $elapsed_ts / 60 % 60, $elapsed_ts % 60, round(($elapsed_ts - floor($elapsed_ts)) * 1000));

            //進捗状況の算出
            $percent = $position / $all_count * 100;
            $progress = round($percent / 2);

            //プログレスバーの構築
            $progress_bar = sprintf($format, round($percent), str_repeat($finished_str, $progress), $current_str, str_repeat($unfinished_str, 50 - $progress), $eta, $position);

            //直前に表示したプログレスバーの文字列長が現在よりも長い場合、消しこみ処理を追加する。
            $current_width = strlen($progress_bar);
            $sol = '';
            if ($before_width > $current_width) {
                $diff_width = $before_width - $current_width;
                $sol = sprintf('%s%s', str_repeat(' ', $diff_width), str_repeat("\x08", $diff_width));
            }
            $before_width = $current_width;

            //現在位置が$all_countと同一になった場合、初期化して終わる。
            if ($all_count == $position) {
                $start_ts = null;
                $before_width = null;
                $position = null;
                $eol = '';
            } else {
                //末尾につける "\r" が全ての答えだった。"\n"に変えると良く判る。
                $eol = "\r";
            }

            //処理の終了
            return sprintf('%s%s%s', $sol, $progress_bar, $eol);
        };
    }
}