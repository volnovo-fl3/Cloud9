-- 条件1
-- nameが「マウスパッド」の商品データを取得する
SELECT id, name, price
FROM products
WHERE
	name = 'マウスパッド';

-- 条件2
-- nameに「パッド」の文字が含まれる商品データを取得する
SELECT id, name, price
FROM products
WHERE
	name LIKE '%パッド%';

-- 条件3
-- nameに「パッド」が入っていない、かつpriceが500円以上の商品データを取得する
SELECT id, name, price
FROM products
WHERE
	name NOT LIKE '%パッド%'
	AND price >= 500;
