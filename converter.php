<?php __HALT_COMPILER(); ?>
                    src/    �>2a        �         src/Phoenix/    �>2a        �         src/Phoenix/Auth/    �>2a        �      !   src/Phoenix/Auth/AuthComandos.phpE	  �>2aE	  �R�          src/Phoenix/Auth/AuthEventos.phpn  �>2an  ��Ӧ�         src/Phoenix/Comandos/    �>2a        �          src/Phoenix/Comandos/AntVoid.php  �>2a  Fu�         src/Phoenix/Comandos/Arco.php  �>2a  0�zh�      "   src/Phoenix/Comandos/ArmorFast.php�  �>2a�  Yl�/�          src/Phoenix/Comandos/Bussola.php�  �>2a�  ��	��      !   src/Phoenix/Comandos/Fireball.php�  �>2a�  b����         src/Phoenix/Comandos/NoFall.php�  �>2a�  "�Ӷ      "   src/Phoenix/Comandos/StaffChat.php%  �>2a%  �,��      *   src/Phoenix/Comandos/StaffChatListener.php  �>2a  �g�?�         src/Phoenix/Main.php�  �>2a�  P�m�         src/Phoenix/PhoenixListener.php�  �>2a�  ���
�         src/Phoenix/Tasks/    �>2a        �      &   src/Phoenix/Tasks/KangaruuCooldown.php�  �>2a�  ���      
   plugin.ymlI  �>2aI  �ͽ�      <?php

namespace Phoenix\Auth;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

use Phoenix\Main;

class AuthComandos implements CommandExecutor{
	
	function __construct(Main $owner){
		$this->owner = $owner;
	}
	
	function onCommand(CommandSender $p, Command $cmd, $label, array $args){
	
		
		if(strtolower($cmd->getName()) == "register"){
			if(count($args) == 0){
				$p->sendMessage("§6[Register] §fPara se cadastrar em nosso servidor digite: §6/register [suasenha]!");
				return;
			}
			
			$senha = $args[0];
			
			if($this->owner->regs->exists(strtolower($p->getName()))){
				$p->sendMessage("§bVocê já é registrado(a) no servidor.");
				return;
			}
			
			$this->owner->auth[$p->getName()] = $p->getName();
			$this->owner->regs->set(strtolower($p->getName()), strtolower($senha));
			$this->owner->regs->save();
			$p->sendMessage("§a[sucesso] §fVocê Está autenticado em nosso servidor! Bom Jogo! :D");
			$p->sendMessage("§fSua senha: §6{$senha}");
		}
		
		if(strtolower($cmd->getName()) == "login"){
			if(count($args) == 0){
				$p->sendMessage("§6[Login] §fPara Fazer seu login em nosso servidor digite: §6/login [suasenha]!.");
				return;
			}
			
			$senha = $args[0];
			
			if(isset($this->owner->auth[$p->getName()])){
				$p->sendMessage("§bVocê já está logado(a) no servidor.");
				return;
			}
			
			if(!$this->owner->regs->exists(strtolower($p->getName()))){
				$p->sendMessage("§fVocê não é registrado(a) no servidor.");
				return;
			}
			
			$cfg = $this->owner->regs->get(strtolower($p->getName()));
			
			if(strtolower($senha) != $cfg){
				$p->sendMessage("§c[Erro] §f- Sua senha está incorreta, tente novamente!.");
				return;
			}
			
			$this->owner->auth[$p->getName()] = $p->getName();
			$p->sendMessage("§a[Sucesso] §fVocê está autenticado em nosso servidor! Bom Jogo! :D");
		}
		
		if(strtolower($cmd->getName()) == "cp"){
			if(count($args) == 0){
				$p->sendMessage("§l§bUSE:§r §b/cp §6[§fNovaSenha§6]");
				return;
			}
			
			$senha = $args[0];
			
			$this->owner->regs->set(strtolower($p->getName()), strtolower($senha));
			$this->owner->regs->save();
			$p->sendMessage("§aSenha alterada com sucesso.");
			$p->sendMessage("§bNova senha: §6{$senha}");
		}
	}
}<?php

namespace Phoenix\Auth;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

use Phoenix\Main;

class AuthEventos implements Listener{
	
	function __construct(Main $owner){
		$this->owner = $owner;
	}
	
	function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();
		$group = $e->getPlayer()->getNameTag();
		
		unset($this->owner->auth[$p->getName()]);
		
		if(!$this->owner->regs->exists(strtolower($p->getName()))){
			$p->sendMessage("§l§6SKY§bSUPREME§r\n§fOlá, bem vindo(a) ao servidor!.\nNosso servidor é focado em Minigames§b/§fSkyWars!\nNosso IP: §bSWSupreme.ddns.net\n§fNossa Porta: §b9719\n§fNosso Twitter:§b@SkySupremePE1\n§l§6SKY§bSUPREME§r\n\n§6[Register] §fPara se cadastrar em nosso servidor digite: §6/register [suasenha]!");
		} else {
			$p->sendMessage("§l§6SKY§bSUPREME§r\n§fOlá, bem vindo(a) ao servidor!.\nNosso servidor é focado em Minigames§b/§fSkyWars!\nNosso IP: §bSkySupreme.ddns.net\n§fNossa Porta: §b25562\n§fNosso Twitter:§b@SkySupremePE1\n§l§6SKY§bSUPREME§r\n\n§6[Login] §fPara Fazer seu login em nosso servidor digite: §6/login [suasenha]!.");
		}
	}
	
	function onMove(PlayerMoveEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
		
		

	}
	
	function onChat(PlayerChatEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
	}
	
	function onInteract(PlayerInteractEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
	}
	
	function onHeld(PlayerItemHeldEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
	}
	
	function onConsume(PlayerItemConsumeEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
	}
	
	function onBlock(PlayerCommandPreprocessEvent $e){
		$p = $e->getPlayer();
		$cmd = explode(" ", $e->getMessage());
		
		if(!isset($this->owner->auth[$p->getName()])){
			if($cmd[0] != "/register" && $cmd[0] != "/login"){
				$e->setCancelled(true);
			}
		}
	}
	
	function onDamage(EntityDamageEvent $e){
		$p = $e->getEntity();
		
		if($p instanceof Player){
			if(!isset($this->owner->auth[$p->getName()])){
				$e->setCancelled(true);
			}
		}
	}
	
	function onBreak(BlockBreakEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
	}
	
	function onPlace(BlockPlaceEvent $e){
		$p = $e->getPlayer();
		
		if(!isset($this->owner->auth[$p->getName()])){
			$e->setCancelled(true);
		}
		
     }
}<?php

namespace Phoenix\Comandos;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;

use Phoenix\Main;
use Phoenix\Comandos\Fake;

 class AntVoid Implements Listener{ 
     public $owner;
	
	public function __construct(Main $owner){
		$this->owner = $owner;
	}
   

	public function onMove(PlayerMoveEvent $event) {
		if($event->getPlayer()->getY() < -5) {
			$p = $event->getPlayer();
			if($p->getLevel()->getName() == "LobbySW"){
			$event->getPlayer()->teleport($event->getPlayer()->getLevel()->getSafeSpawn());
   $p->sendMessage("§8- §5Você Foi Teletransportado Para O Spawn");
		}
	}
	}
	
	public function onDamage(EntityDamageEvent $event) {
		if($event->getEntity() instanceof Player && $event->getEntity()->getY() < 0) {
		$p = $event->getPlayer();
		if($p->getLevel()->getName() == "LobbySW"){
			$event->setCancelled(true);
		}
	}
	}

public function onQuit(PlayerQuitEvent $event){
if(isset($this->players[($name = strtolower($event->getPlayer()->getName()))])){
			    array_push($this->nicks, $this->players[$name]);
					unset($this->players[$name]);
					
			}
	  }
}<?php 

namespace Phoenix\Comandos;

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
 
use Phoenix\Main;

 class Arco Implements Listener{ 
     public $owner;
	
	public function __construct(Main $owner){
		$this->owner = $owner;
	}
   
   public function onHit(EntityDamageEvent $e){
   if($e instanceof EntityDamageByChildEntityEvent){
   
   $vitima = $e->getEntity();
   $vilao = $e->getDamager();
      
   $vilao->sendPopup("§5".$vitima->getName().": §fEstá Com§5 ".$vitima->getHealth()."§7 | §5".$vitima->getMaxHealth()."§f Life");
   
   $vilao->getLevel()->addSound(new AnvilFallSound($vilao));
   
   $vitima->sendPopup("§5".$vilao->getName()." §fAtingiu Você Em Cheio");
   }
  }
  public function onMove(PlayerMoveEvent $e){
  $p = $e->getPlayer();
  $b = $p->getLevel()->getBlock($p->floor()->subtract(0, 1));
  if($b->getId() == 165){
  $p->setMotion(new Vector3(0, 3.0, 0));
  $p->sendPopup("§5Ultra§fBoost");
  $p->getLevel()->addSound(new AnvilFallSound($p));
   }
  }
 }<?php

namespace Phoenix\Comandos;;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\inventory\InventoryBase;
use pocketmine\Player;
use pocketmine\Server;

use Phoenix\Main;

 class ArmorFast Implements Listener{ 
     public $owner;
	
	public function __construct(Main $owner){
		$this->owner = $owner;
	}

  public function onTouch(PlayerInteractEvent $event){
    $player = $event->getPlayer();
    // Diamond Armor
    if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 310){
      $i= Item::get(310, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setHelmet($i);
    $player->sendPopup("§fCapacete Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 311){
      $i= Item::get(311, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setChestplate($i);
    $player->sendPopup("§5Peitoral Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 312){
      $i= Item::get(312, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setLeggings($i);
     $player->sendPopup("§fCalça Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 313){
      $i= Item::get(313, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setBoots($i);
    $player->sendPopup("§5Bota Equipado");
      }
      // Iron Armor
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 306){
      $i= Item::get(306, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setHelmet($i);
    $player->sendPopup("§fCapacete Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 307){
      $i= Item::get(307, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setChestplate($i);
    $player->sendPopup("§5Peitoral Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 308){
      $i= Item::get(308, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setLeggings($i);
    $player->sendPopup("§fCalça Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 309){
      $i= Item::get(309, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setBoots($i);
    $player->sendPopup("§5Bota Equipado");
      }
      // Chain Armor]
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 302){
      $i= Item::get(302, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setHelmet($i);
    $player->sendPopup("§fCapacete Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 303){
      $i= Item::get(303, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setChestplate($i);
    $player->sendPopup("§5Peitoral Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 304){
      $i= Item::get(304, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setLeggings($i);
    $player->sendPopup("§fCalça Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 305){
      $i= Item::get(305, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setBoots($i);
    $player->sendPopup("§5Bota Equipado");
      }
      // Gold
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 314){
      $i= Item::get(314, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setHelmet($i);
    $player->sendPopup("§fCapacete Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 315){
      $i= Item::get(315, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setChestplate($i);
    $player->sendPopup("§5Peitoral Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 316){
      $i= Item::get(316, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setLeggings($i);
    $player->sendPopup("§fCalça Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 317){
      $i= Item::get(317, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setBoots($i);
    $player->sendPopup("§5Bota Equipado");
      }
      // Leather Armor
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 298){
      $i= Item::get(298, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setHelmet($i);
    $player->sendPopup("§fCapacete Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 299){
      $i= Item::get(299, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setChestplate($i);
    $player->sendPopup("§5Peitoral Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 300){
      $i= Item::get(300, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setLeggings($i);
    $player->sendPopup("§fCalça Equipado");
      }
          if($event->getPlayer()->getInventory()->getItemInHand()->getId() === 301){
      $i= Item::get(301, 0, 1);
      $player->getInventory()->remove($i);
      $player->getInventory()->setBoots($i);
    $player->sendPopup("§5Bota Equipado");
      }
  }
}<?php

namespace Phoenix\Comandos;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;

use Phoenix\Main;

class Bussola implements Listener{
	public function __construct(Main $owner) {
	$this->owner = $owner;
		}
	
	public $prefix = "§5H§fS";
		
		public function onInteract(PlayerInteractEvent $ev){
        $player = $ev->getPlayer();
        $item = $ev->getItem();
        $block = $ev->getBlock();
        if($item->getId() == 345){
            $closest = "nullbody";
            $lastSquare = -1;
            foreach($this->getServer()->getOnlinePlayers() as $p){
                if($p !== $player){
                        $x = $p->x - $player->x;
                        $z = $p->z - $player->z;
                        $square = abs($x) + abs($z);
                        if($lastSquare === -1 or $lastSquare > $square){
                            $closest = $p->getName();
                            $lastSquare = round($square);
                            $player->sendPopup($this->prefix." §5".$closest." §festá a §5".$lastSquare." §fblocos de você");
		
	}
}
}
}
}
}<?php 

namespace Phoenix\Comandos;
 
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\{CompoundTag, DoubleTag, ListTag, FloatTag};
use pocketmine\entity\{Entity, PrimedTNT};
use pocketmine\event\player\PlayerInteractEvent;
 
use Phoenix\Main;

class Fireball implements Listener {
public $owner;

public function __construct(Main $owner){
$this->owner = $owner;
	}

public function onEnable(){ $this->getServer()->getPluginManager()->registerEvents($this, $this);
 $this->getLogger()->info("Plugin FireBall Ativado Feito Por MasterNice");
 } public function onInteract(PlayerInteractEvent $event){ $player = $event->getPlayer();
 $inv = $event->getItem();
 if($inv->getId() == 385){ $hand = $player->getInventory()->getItemInHand();
 $handclone = clone $player->getInventory()->getItemInHand();
 $handclone->setCount($handclone->getCount() - 1);
 $player->getInventory()->removeItem($hand);
 $player->getInventory()->addItem($handclone);
 $nbt = new CompoundTag("", [ "Pos" => new ListTag("Pos", [ new DoubleTag("", $player->x), new DoubleTag("", $player->y + $player->getEyeHeight()), new DoubleTag("", $player->z)]), "Motion" => new ListTag("Motion", [ new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)), new DoubleTag("", -sin($player->pitch / 180 * M_PI)), new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))]), "Rotation" => new ListTag("Rotation", [ new FloatTag("", $player->yaw), new FloatTag("", $player->pitch)]), ]);
 $bomb = Entity::createEntity("PrimedTNT", $player->chunk, $nbt, true);
 $bomb->setMotion($bomb->getMotion()->multiply(2));
 } } }<?php

namespace Phoenix\Comandos;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerQuitEvent;

use Phoenix\Main;

Class NoFall implements Listener{
public $owner;

public function __construct(Main $owner){
		$this->owner = $owner;
	}
	
	public function damageHandler(EntityDamageEvent $event){
		$entity = $event->getEntity();
		$cause = $event->getCause();
			if($cause == EntityDamageEvent::CAUSE_FALL){
				$event->setCancelled(true);
				}
		}

public function onQuit(PlayerQuitEvent $e){
$p = $e->getPlayer();
$cmd = "fake off";
Server::getInstance()->getCommandMap()->dispatch($p, $cmd);
  }
}<?php

namespace Phoenix\Comandos;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

use Phoenix\Main;

class StaffChat implements CommandExecutor{
	
	public function __construct(Main $owner){
		$this->owner= $owner;
	}
	
	function onCommand(CommandSender $p, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName() == "Sc")){
	    if(!isset($args[0])){
			$p->sendMessage("§l§6USE:§r §f/Sc §6[§bOn§f|§bOff§6]");
			return;
			}
		}
		if(!$p->hasPermission("supreme.perm")){
			$p->sendMessage("§f- §cVocê Não é Autorizado, Para Usa Esse Comando!");
			return;
		}
		if(isset($args[0])){
			switch(strtolower($args[0])){
				case "on":
				$this->owner->schat[strtolower($p->getName())] = $args[0];
				$p->sendMessage("§f- §6StaffChat §aAtivado.");
				break;
				case "off":
				unset($this->owner->schat[strtolower($p->getName())]);
				$p->sendMessage(" §f- §6StaffChat §cDesativado.");
				break;
			}
	    }
    }
}<?php

namespace Phoenix\Comandos;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as color;
use pocketmine\event\player\PlayerChatEvent;

use Phoenix\Main;

class StaffChatListener implements Listener{
	public $owner;
	
	public function __construct(Main $owner){
		$this->owner = $owner;
	}
	
	public function onChat(PlayerChatEvent $ev){
		$pla
