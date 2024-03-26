Разработать API эндотипы (CRUD) на основе Laravel 10 для управления покемонами.
Покемоны имеют:
- имя на английском языке
- изображение
- порядковый номер (индекс сортировки в листинге)
- форму (только голова – head, голова и ноги – head_legs, плавники – fins, крылья – wings)
- локацию
- способности (название на двух языках, изображение)
- уникальными для покемонов является только имя и порядковый номер
  Критично:
- валидация запросов
- изображения хранятся в локальном хранилище и доступны по отдельным эндотипам
- корректная структура и названия роутов их группировка
  Плюсы:
- в листинге учтены сортировка и фильтрация по локации
- локации отнесены к регионам (Volcano, Cinnabar Gym, Mansion, Cinnabar Lab – Kanto, Hoenn), предусмотрена вложенность N уровня локаций, предусмотрена фильтрация по регионам.

Документация:

/api/pokemones/ - метод get. Получить список всех покемонов

/api/pokemones/?region={n} – метод get. Получить покемонов из определенного региона и входящие в него подрегионы (где n это id региона)

/api/pokemones/ - метод post - создать покемона.

    пример request:
        name = 'pokemon name',
        image = 'images/pokemon_portrait/resized/pic.jpg' OR image_file = any file (peg,png,jpg,gif)
        shapes_id = '[1, 3]'
        abilities_id = '[1, 2]'

/api/pokemones/{id} – метод get. Получить покемона с определенным id (id int)

/api/pokemones/{id} - метод post. Редактировать покемона с определенным id (id int)

    пример request:
        name = 'pokemon name',
        image = 'images/pokemon_portrait/resized/pic.jpg' OR image_file = any file (peg,png,jpg,gif)
        shapes_id = '[1, 3]'
        abilities_id = '[1, 2]'
        _method = 'put'

/api/pokemones/{id} - метод delete. Удалить покемона с определенным id (id int)

/api/pokemoneimages/ - метод get. Получить список всех доступных изображений для покемонов

/api/pokemoneimages/ - метод post - создать изображение для покемона.

    пример request:
        image_file = any file (peg,png,jpg,gif)

/api/pokemoneimages/{id} – метод get. Получить описание изображения для покемона. (id это название файла)

/api/pokemoneimages/{id} - метод post. Редактировать изображение для покемона (id это название файла который хотим редактировать)

    пример request:
        image_file = any file (peg,png,jpg,gif)
		_method = 'put'

/api/pokemoneimages/{id} - метод delete. Удалить изображения для покемона. (id это название файла)

/api/abilities/ - метод get. Получить список всех способностей.

/api/abilities/ - метод post - создать способность.

    пример request:
        name = 'ability name',
		name_lang_ru = 'название на русском'
        image = 'images/pokemon_ability/resized/pic.jpg' OR image_file = any file (peg,png,jpg,gif)

/api/abilities/{id} – метод get. Получить способность с определенным id (id int)

/api/abilities/{id} - метод post. Редактировать способность с определенным id (id int)

    пример request:
        name = 'ability name',
		name_lang_ru = 'название на русском'
        image = 'images/pokemon_ability/resized/pic.jpg' OR image_file = any file (peg,png,jpg,gif)
        _method = 'put'

/api/abilities/{id} - метод delete. Удалить способность с определенным id (id int)
