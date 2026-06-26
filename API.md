# ElePHPant.me API

A free, public JSON API for the PHP community. No authentication required.

**Base URL:** `https://elephpant.me/api`

---

## Endpoints

### List all elephpants

```
GET /api/elephpants
```

Returns a paginated list of all elephpant species, ordered by year and name. 20 results per page.

**Query parameters**

| Parameter | Type    | Description              |
|-----------|---------|--------------------------|
| `page`    | integer | Page number (default: 1) |

**Example request**

```bash
curl https://elephpant.me/api/elephpants
curl https://elephpant.me/api/elephpants?page=2
```

**Example response**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Original Blue",
      "description": "First Blue",
      "sponsor": "Nexen / Alter Way",
      "year": 2007,
      "image_url": "https://elephpant.me/storage/elephpants/1-original-blue.jpg",
      "owners": 142,
      "url": "https://elephpant.me/api/elephpants/1"
    }
  ],
  "links": {
    "first": "https://elephpant.me/api/elephpants?page=1",
    "last": "https://elephpant.me/api/elephpants?page=5",
    "prev": null,
    "next": "https://elephpant.me/api/elephpants?page=2"
  },
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 91
  }
}
```

---

### Get a single elephpant

```
GET /api/elephpants/{id}
```

Returns details for a specific elephpant species by ID.

**Example request**

```bash
curl https://elephpant.me/api/elephpants/1
```

**Example response**

```json
{
  "data": {
    "id": 1,
    "name": "Original Blue",
    "description": "First Blue",
    "sponsor": "Nexen / Alter Way",
    "year": 2007,
    "image_url": "https://elephpant.me/storage/elephpants/1-original-blue.jpg",
    "owners": 142,
    "url": "https://elephpant.me/api/elephpants/1"
  }
}
```

**Error responses**

| Status | Description              |
|--------|--------------------------|
| `404`  | Elephpant ID not found   |

---

### Get a user's herd

```
GET /api/herd/{username}
```

Returns the full herd of a registered collector, including stats and a list of all collected elephpants with quantities.

**Example request**

```bash
curl https://elephpant.me/api/herd/junior_grossi
```

**Example response**

```json
{
  "username": "junior_grossi",
  "name": "Junior Grossi",
  "avatar": "https://...",
  "country": "br",
  "twitter": "junior_grossi",
  "mastodon": null,
  "herd_url": "https://elephpant.me/herd/junior_grossi",
  "stats": {
    "total": 42,
    "unique": 30,
    "spare": 12
  },
  "elephpants": [
    {
      "id": 1,
      "name": "Original Blue",
      "description": "First Blue",
      "sponsor": "Nexen / Alter Way",
      "year": 2007,
      "image_url": "https://elephpant.me/storage/elephpants/1-original-blue.jpg",
      "quantity": 2
    }
  ]
}
```

**Error responses**

| Status | Description                    |
|--------|--------------------------------|
| `403`  | User's herd is set to private  |
| `404`  | Username not found             |

---

## Field reference

### Elephpant object

| Field       | Type            | Description                                          |
|-------------|-----------------|------------------------------------------------------|
| `id`        | integer         | Unique identifier                                    |
| `name`      | string          | Name of the elephpant                                |
| `description` | string        | Short description                                    |
| `sponsor`   | string          | Company or person who sponsored this elephpant       |
| `year`      | integer         | Year of release                                      |
| `image_url` | string or null  | Absolute URL to the elephpant image                  |
| `owners`    | integer         | Number of collectors who own at least one            |
| `url`       | string          | Canonical API URL for this elephpant                 |

### Herd object

| Field        | Type    | Description                                            |
|--------------|---------|--------------------------------------------------------|
| `username`   | string  | The collector's username                               |
| `name`       | string  | Display name                                           |
| `avatar`     | string  | URL to the user's avatar image                         |
| `country`    | string  | ISO 3166-1 alpha-2 country code                        |
| `twitter`    | string or null | Twitter/X handle (without @)                    |
| `mastodon`   | string or null | Mastodon handle                                  |
| `herd_url`   | string  | URL to the public herd page on elephpant.me            |
| `stats.total`  | integer | Total number of elephpants (including duplicates)    |
| `stats.unique` | integer | Number of distinct species owned                     |
| `stats.spare`  | integer | Number of spare elephpants available for trading     |
| `elephpants`   | array   | List of collected elephpants with `quantity` field   |
