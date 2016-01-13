{
    "title": "Optimizing MySQL performance using bitmasks",
    "description": "A short tutorial on how to use bitmasks to improve performance of your database queries.",
    "date": "2016-01-10",
    "slug": "optimizing-mysql-performance-with-bitmasks",
    "author": "Simon",
    "tag": "php,mysql,webdevelopment",
    "category": "PHP"
}

When it comes to (mysql) database optimization the most common practice is to
[normalize your database](https://en.wikipedia.org/wiki/Database_normalization) structure. In general this is a good
idea but when it comes to performance a completely normalized database is not always the best solution.

For a better understanding of the following text I will use the following example data:

```
Table shirts
+----+----------+
| id | motive   |
+----+----------+
|  1 | Homer    |
|  2 | Marge    |
|  3 | Bart     |
+----+----------+


Table colors
+----+----------+
| id | color    |
+----+----------+
|  1 | Red      |
|  2 | Blue     |
|  4 | Green    |
|  8 | Black    |
+----+----------+
```

We have a table containing t-shirts and another table containing the colors. Every shirt can be available in different
colors - but not every shirt is guaranteed to be available in every color. So what we need to do is to create a new
table to store the information which shirt is available in which color. This would probably look like this:

```
Table: shirts_x_colors
+----------+------------+
| shirt_id | color_id   |
+----------+------------+
|        1 |          1 |
|        1 |          4 |
|        2 |          2 |
|        2 |          8 |
|        3 |          2 |
+----------+------------+
```

This table now tells us which shirt is available in which color - like e.g. shirt "Marge" is available in red and black.
So if we would like to have a list of all shirts and available colors we would need to query 3 tables and probably even
use a GROUP_CONCAT to get a comma-separated list of the colors. Something like:
<pre class="lang-sql"><code>SELECT shirts.motive, GROUP_CONCAT(colors.color)
FROM shirts
JOIN shirts_x_colors ON shirts.id = shirts_x_colors.shirt_id
JOIN colors ON shirts_x_colors.color_id = colors.id
GROUP BY shirts.id</code></pre>

As you can imagine this type of queries can become relatively slow because your shirts probably not only have a color
but also different sizes, materials, ...

## Using bitmasks

Bitmasks can help to improve this kind of queries. As you probably noticed the IDs within the colors table are not just
simply an auto-incremented integer but only numbers which are powers of two (1,2,4,8,16,32,...). We can now extend our
shirts table like this:

```
Table shirts
+----+----------+--------+
| id | motive   | colors |
+----+----------+--------+
|  1 | Homer    |      5 |
|  2 | Marge    |     10 |
|  3 | Bart     |      2 |
+----+----------+--------+
```

I simply added a new column called "colors" which contains the sum of all available color-ids for each shirt - e.g:
Shirt "Homer" is available in Red (1) and Green (4) so "colors" is 1 + 4 = 5. The cool thing about bitmasks is that
this sum is always unique - meaning only from this sum we know in which colors a shirt is available.

Here is some sample code converting the sum of our ids back into the single values:

<pre class="lang-php"><code>
function reverseBitmask($bitmask)
{
    $bin = decbin($bitmask);
    $total = strlen($bin);
    $stock = [];
    for ($i = 0; $i < $total; $i++) {
        if ($bin{$i} != 0) {
            $bin_2 = str_pad($bin{$i}, $total - $i, 0);
            array_push($stock, bindec($bin_2));
        }
    }
    return $stock;
}

print_r(reverseBitmask(5));
</code></pre>

But there's more: What if we would like to search for all shirts available in color red or green?
We can do this with one simple query. The sum of red and green ids would be 5 so our query looks like this:

<pre class="lang-sql"><code>
SELECT * FROM shirts WHERE colors & 5 > 0;
</code></pre>

This would return a list of all shirts available in either red or green.

## Conclusion

Using bitmasks can help you to improve the performance of your SQL queries in some cases. Of course you have to carefully
check if this technique is useful in your case. For example I would not suggest using bitmask if the list of attributes
(e.g the colors) can become very long. Additionally you have to be clear that you will store redundant data in your
database if you change the structure of your tables according to the above example.

Using bitmasks is no magic-bullet to solve database performance issues but it can help in some cases - and I hope now
you have a basic understanding of how bitmasks work and can maybe use them in your next project.

 Happy coding!