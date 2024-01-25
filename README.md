# RanksPlus Plugin

RanksPlus is a PocketMine-MP plugin designed to enhance your server's ranking system by providing customizable ranks with associated permissions, prefixes, and suffixes.

## Features

- **Customizable Ranks**: Define unique ranks for your server, each with its own set of permissions.
- **Permissions System**: Grant specific permissions to each rank to control access to commands and features.
- **Prefixes and Suffixes**: Personalize player name tags with custom prefixes and suffixes for each rank.
- **Easy Configuration**: Configure and customize ranks, permissions, prefixes, and suffixes via YAML files.

## Installation

1. Download the plugin from the [releases page](https://github.com/BajanVlogs/RanksPlus/releases).
2. Place the plugin JAR file into the `plugins` folder of your PocketMine-MP server.
3. Start or restart your server.

## Configuration

### Ranks Configuration (ranks.yml)

Edit the `ranks.yml` file to define your server's ranks, each with its associated prefix, suffix, and permissions.

Example `ranks.yml`:

```yaml
default:
  prefix: "&7"
  suffix: "&r"
  permissions:
    - "default.permission"

vip:
  prefix: "&a[VIP] "
  suffix: "&r"
  permissions:
    - "vip.permission"
