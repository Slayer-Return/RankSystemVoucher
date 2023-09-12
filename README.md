# RankSystemVoucher

A simple plugin addon for <a target="_blank" href="https://poggit.pmmp.io/p/RankSystem/">RankSystem</a> from IvanCraft623.

## Features

| RankSystemVoucher   | Description                     |
| ------------------- | ------------------------------- |
| Customizable Config | Commands, Messages, Permissions |
| Customizable Item   | Custom Lore, Custom Name        |

## Commands

| Commands                                      | Description                                      | Permissions                 | Aliases      |
| --------------------------------------------- | ------------------------------------------------ | --------------------------- | ------------ |
| `/ranksystemvoucher <player> <rank> <amount>` | `RankSystemVoucher Commands, default amount = 1` | `ranksystemvoucher.command` | `/ranksgive` |

## How To Use

- Create your own RankSystemVoucher item in plugin_data/RankSystemVoucher/rank_voucher.yml
- Get your item with command /ranksystemvoucher YourName RankName
- Hold the item
- Click on block

## Example Config

```yaml
example: #ranks_name
  item: paper #item
  customname: §aRankSystemVoucher §eExample #custom_name
  lore:
    - "§aRank §eExample"
    - "§7(Right-Click) to claim rank"
  permission: example #permission
  commands:
    - "ranksystem setrank {player} example"
    - "ranksystem removerank {player} {old-rank}"
  message: "§eCongratulations you just got a Rank {rank} from RankSystemVoucher!" #message
```

## Important

You need permission to use the item.
Set permissions like this, for example: `ranksystemvoucher.ranks.example`

## Credits

<a target="_blank" href="https://icons8.com/icon/_UuGPc1g68Z5/ticket">Voucher</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
