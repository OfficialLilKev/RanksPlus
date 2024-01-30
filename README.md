# RanksPlus Plugin

RanksPlus is a PocketMine-MP plugin designed to enhance your server's ranking system by providing customizable ranks with associated permissions, prefixes, and suffixes.

## Features

- Customizable Ranks: Define unique ranks for your server, each with its own set of permissions.
- Intergration with PiggyFactions full support
- Permissions System: Grant specific permissions to each rank to control access to commands and features.
- Prefixes and Suffixes: Personalize player name tags with custom prefixes and suffixes for each rank.
- Easy Configuration: Configure and customize ranks, permissions, prefixes, and suffixes via YAML files.

## Installation

1. Download the plugin from the releases page.
2. Place the plugin JAR file into the plugins folder of your PocketMine-MP server.
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

Permissions Configuration (permissions.yml)
Edit the permissions.yml file to assign specific permissions to individual players.

Example permissions.yml:

player1:
  - "example.permission"

player2:
  - "another.permission"
  - "some.other.permission"

Usage
Define Ranks: Edit ranks.yml to create and configure your server's ranks.
Set Permissions: Assign permissions to each rank in the ranks.yml file.
Customize Prefixes and Suffixes: Personalize player name tags by adjusting prefixes and suffixes in ranks.yml.
Restart Server: Restart your server to apply the changes.
New Command and Permission
/setrank
Description: Set the rank of a player
Usage: /setrank <rank> <player>
Permission: ranksplus.setrank
Support
For any issues, feature requests, or questions, please open an issue.
