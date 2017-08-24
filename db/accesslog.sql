/*
-- テーブルをそのまま表示
SELECT *
FROM access;
*/

-- 課題
SELECT
	-- datetime型→date型への変換 / http://amidagamine.com/notes/3258
	-- CAST(access_datetime AS DATE) AS date
	DATE(access_datetime) AS date
	, COUNT(access_datetime) AS pv
	-- 重複を省いてカウント / http://logic.moo.jp/data/archives/466.html
	, COUNT(DISTINCT user_id) AS uu
FROM
	access
GROUP BY
	DATE(access_datetime);