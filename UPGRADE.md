## 3.x.x to 4.0.0

- Lumen is no longer supported.

## 2.x.x to 3.0.0

- A new configuration option, `driver`, has been added. You can see it [here](https://github.com/bepsvpt/blurhash/blob/3.0.0/config/blurhash.php#L5-L11) and add it to your own config file.
- The `resized-image-max-width` setting is now called `resized-max-size`.
- The `encode` method accepts only `UploadedFile` and `string` (for file path).
- `decode` returns a resource based on the driver you've configured.
