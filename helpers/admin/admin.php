<?php
	if ($update) {
		if (isset($update->my_chat_member->new_chat_member)) {
			if ($update->my_chat_member->new_chat_member->status == 'kicked') {
				$db->withSqlQueryWithOutEscapeString("UPDATE users SET del='1', deleted_at=NOW() WHERE fromid='" . $update->my_chat_member->from->id . "'");

			}elseif ($update->my_chat_member->new_chat_member->status == 'left') {
				$db->withSqlQueryWithOutEscapeString("UPDATE users SET del='1', deleted_at=NOW() WHERE fromid='" . $update->my_chat_member->chat->id . "'");
			}
		}
		$home = [
			[
				['text'=>"Admin qo'shish", 'callback_data'=>'addAdmin'],
				['text'=>"Admin o'chirish", 'callback_data'=>'removeAdmin']
			],
			[
				['text'=>"Kanal sozlash", 'callback_data'=>'settingChannel'],
				['text'=>"Reklama yuborish", 'callback_data'=>'sendAd']
			],
		];
		$cancel = [
			[
				['text'=>'Bekor qilish', 'callback_data'=>'home'],
			],
			[
				['text'=>'Ortga', 'callback_data'=>'home'],
			]
		];
		$settingChannel = [
			[
				['text'=>'Majburiy azolik on/off', 'callback_data'=>'changeJoinChannel'],
			],
			[
				['text'=>"Kanal o'chirish", 'callback_data'=>'removeChannel'],
				['text'=>"Kanal qo'shish", 'callback_data'=>'addChannel'],
			],
			[
				['text'=>'Ortga', 'callback_data'=>'home'],
			]
		];
		$sendAd = [
			[
				['text'=>'🇷🇺 Rus userlarga ', 'callback_data'=>'toRus'],
				['text'=>"🇺🇸 Ingliz userlarga ", 'callback_data'=>'toUs'],
			],
			[
				['text'=>"🇺🇿 Uzbek userlarga ", 'callback_data'=>'toUz'],
				['text'=>"Til tanlanmaganlarga ", 'callback_data'=>'toNotSelectedLang'],
			],
			[
				['text'=>"Guruhlarga ", 'callback_data'=>'toGroup'],
			],
			[
				['text'=>'Ortga', 'callback_data'=>'cancelSendAd'],
			]
		];
		$sendConfirm = [
			[
				['text'=>'Yuborish ✅', 'callback_data'=>'sendConfirm'],
			],
			[
				['text'=>'Bekor qilish', 'callback_data'=>'cancelSend'],
			],
			[
				['text'=>'Ortga', 'callback_data'=>'home'],
			]
		];
		if (isset($update->message)) {
			if (isAdmin($fromid)) {
				if (strtolower($text) == '/admin') {
					$db->updateWhere('admins',
						[
							'menu'=>'',
							'step'=>''
						],
						[
							'fromid'=>$fromid,
							'cn'=>'='
						]
					);

					$db->withSqlQuery('SELECT count(id) as id FROM users WHERE 1');
					$allUsers = $db->fetch()['id'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE chat_type='private'");
					$only_users = $db->fetch()['id'];

					// $db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE chat_type='private' AND del='0'");
					// $only_active_users = $db->fetch()['id'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE chat_type='group'");
					$only_groups = $db->fetch()['id'];

					// $db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE chat_type='group' AND del='0'");
					// $only_active_groups = $db->fetch()['id'];

					// $db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE DAY(created_at) = " . date('d', strtotime('-1 day')) . " AND MONTH(created_at) = " . date('m', strtotime('-1 day')) . " AND YEAR(created_at) = " . date('Y', strtotime('-1 day')));
					// $yester_day = $db->fetch()['id'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE DAY(created_at) = " . date('d', strtotime('-1 day')) . " AND MONTH(created_at) = " . date('m') . " AND YEAR(created_at) = " . date('Y') . " AND del='0'");
					$yester_day_plus = $db->fetch()['id'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE DAY(created_at) = " . date('d') . " AND MONTH(created_at) = " . date('m') . " AND YEAR(created_at) = " . date('Y') . " AND del='0'");
					$this_day_plus = $db->fetch()['id'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE DAY(deleted_at) = " . date('d') . " AND MONTH(deleted_at) = " . date('m') . " AND YEAR(deleted_at) = " . date('Y') . " AND del='1'");
					$this_day_minus = $db->fetch()['id'];

					// $db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE MONTH(created_at) = " . date('m') . " AND YEAR(created_at) = " . date('Y'));
					// $this_month = $db->fetch()['id'];

					// $db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM users WHERE YEAR(created_at) = " . date('Y'));
					// $this_year = $db->fetch()['id'];

					$db->selectWhere('sendAd',[
						[
							'id'=>'1',
							'cn'=>'>='
						]
					]);
					$sended_count = $db->fetch()['sended_count'];

					$db->selectWhere('sendAd',[
						[
							'id'=>'1',
							'cn'=>'>='
						]
					]);
					
					$sended_user_count = $db->fetch()['sended_user_count'];

					$db->withSqlQueryWithOutEscapeString("SELECT count(id) as id FROM active_users");
					$active_user = $db->fetch()['id'];

					$matn = "<b>Bot statistics</b>\nTotal: <b>$allUsers (+$this_day_plus) (-$this_day_minus)</b>\n\n👥 Users: <b>$only_users</b>\n\n🗣 Groups: <b>$only_groups</b>\n\n👤 Unique users: <b>$active_user</b>\n\n🌗Yesterday's: <b>+$yester_day_plus</b>" .  (($sended_count>0) ? "\n\n🤝 Reklama yuborilganlar: <b>" . $sended_count . "</b>\nMuvoffaqiyatli yuborilganlar: <b>" . $sended_user_count . "</b>" : '');

					$bot->sendChatAction('typing', $fromid)->setReplyKeyboard([[['text'=>'/admin']]])->sendMessage($matn);

					$bot->setInlineKeyboard($home)->sendMessage("Kerakli bo'limni tanlang:");
					exit();
				}else if(mb_stripos($text, "/remove_admin_")!==false){
					$deleteId = (int)explode("/remove_admin_", $text)[1];
					if ($deleteId == $fromid) {
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage("Siz o'zingizni o'zingiz adminlik huquqidan mahrum qila olmaysiz.");
						exit();
					}
					$supperAdmin = mysqli_num_rows(
						$db->selectWhere('admins',[
							[
								'fromid'=>$fromid,
								'cn'=>'='
							],
							[
								'status'=>'supperadmin',
								'cn'=>'='
							]
						])
					);
					if (!$supperAdmin) {
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage("Faqat supperadminlar bot adminlarini o'chirishi mumkin!");
						exit();
					}
					
					$db->deleteWhere('admins',[
						[
							'fromid'=>$deleteId,
							'cn'=>'='
						]
					]);
					$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage("Muffaqiyatli.");
						exit();
				}else if(mb_stripos($text, "/remove_channel_")!==false){
					$deleteId = (int)explode("/remove_channel_", $text)[1];
					
					$db->deleteWhere('channels',[
						[
							'id'=>$deleteId,
							'cn'=>'='
						]
					]);
					$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage("Kanal muffaqiyatli o'chirildi.");
						exit();
				}
				$admin = mysqli_fetch_assoc(
					$db->selectWhere('admins',[
						[
							'fromid'=>$fromid,
							'cn'=>'='
						]
					])
				);

				if ($admin['menu'] == 'addAdmin') {
					if ((int)$text) {
						$checkAdmin = mysqli_num_rows($db->selectWhere('admins',[
							[
								'fromid'=>(int)$text,
								'cn'=>'='
							]
						]));
						if ($checkAdmin) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage('Ushbu odam botda avvaldan admin.');
							exit();
						}
						$db->insertInto('admins',[
							'fromid'=>(int)$text,
							'menu'=>'',
							'step'=>'',
							'status'=>'admin'
						]);
						$db->updateWhere('admins',
							[
								'menu'=>'',
								'step'=>''
							],
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						);
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage($text . " ID egasi endi bot adminstratori.");
						exit();
					}else if (!$message->forward_from) {
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage("Eslatma admin qilmoqchi bo'lgan odamning telegram sozlamarida uzatilgan habarlar hamama uchun ochiq bo'lishligi kerak!");
						exit();
					}
					$checkAdmin = mysqli_num_rows($db->selectWhere('admins',[
						[
							'fromid'=>$message->forward_from->id,
							'cn'=>'='
						]
					]));
					if ($checkAdmin) {
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage(html($message->forward_from->first_name ?? $message->forward_from->first_name) . ' botda avvaldan admin.');
						exit();
					}
					$db->insertInto('admins',[
						'fromid'=>$message->forward_from->id,
						'menu'=>'',
						'step'=>'',
						'status'=>'admin'
					]);
					$db->updateWhere('admins',
						[
							'menu'=>'',
							'step'=>''
						],
						[
							'fromid'=>$fromid,
							'cn'=>'='
						]
					);
					$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage(html($message->forward_from->first_name ?? $message->forward_from->first_name) . " Endi bot adminstratori.");
				}else if ($admin['menu'] == 'addChannel') {
					if ((int)$text) {
						$getChat = $bot->getChat($text)->result();
						if (!$getChat->ok) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($settingChannel)->sendMessage('Bot yoki siz kanal adminstratori emassiz.');
							exit();
						}
						$checkChannel = mysqli_num_rows($db->selectWhere('channels',[
							[
								'target'=>(int)$text,
								'cn'=>'='
							]
						]));
						if ($checkChannel) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage("Ushbu kanal botga avvaldan qo'shilgan.");
							exit();
						}
						$db->insertInto('channels',[
							'name'=>$getChat->result->title,
							'target'=>(int)$text
						]);
						$db->updateWhere('admins',
							[
								'menu'=>'',
								'step'=>''
							],
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						);
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage($getChat->result->title . " botga muffaqiyatli ulandi.");
					}else if ($message->forward_from_chat) {
						$getChat = $bot->getChat($message->forward_from_chat->id)->result();
						if (!$getChat->ok) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($settingChannel)->sendMessage('Bot yoki siz kanal adminstratori emassiz.');
							exit();
						}
						$checkChannel = mysqli_num_rows($db->selectWhere('channels',[
							[
								'target'=>(int)$message->forward_from_chat->id,
								'cn'=>'='
							]
						]));
						if ($checkChannel) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage("Ushbu kanal botga avvaldan qo'shilgan.");
							exit();
						}
						$db->insertInto('channels',[
							'name'=>$getChat->result->title,
							'target'=>(int)$message->forward_from_chat->id
						]);
						$db->updateWhere('admins',
							[
								'menu'=>'',
								'step'=>''
							],
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						);
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage($getChat->result->title . " botga muffaqiyatli ulandi.");
					}else if(mb_stripos($text, '@')!==false){
						$getChat = $bot->getChat($text)->result();
						if (!$getChat->ok) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($settingChannel)->sendMessage('Bot yoki siz kanal adminstratori emassiz.');
							exit();
						}
						$checkChannel = mysqli_num_rows($db->selectWhere('channels',[
							[
								'target'=>(int)$getChat->result->id,
								'cn'=>'='
							]
						]));
						if ($checkChannel) {
							$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage("Ushbu kanal botga avvaldan qo'shilgan.");
							exit();
						}
						$db->insertInto('channels',[
							'name'=>$getChat->result->title,
							'target'=>(int)$getChat->result->id
						]);
						$db->updateWhere('admins',
							[
								'menu'=>'',
								'step'=>''
							],
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						);
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($home)->sendMessage($getChat->result->title . " botga muffaqiyatli ulandi.");
					}else{
						$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($cancel)->sendMessage("Botga kanal qo'shish uchun kanal user yoki ID sini yuboring, yoki kanaldan forward qiling.\n\nEslatma botga qo'shilayotgan kanalda bot admin bo'lishligi zarur.");
					}
				}else if($admin['menu'] == 'sendAd' && $admin['step'] == '0'){
					$db->updateWhere('sendAd',
						[
							'chat_id'=>$fromid,
							'message_id'=>$message_id,
							'reply_markup'=>$reply ? json_encode($reply) : json_encode(false),
						],
						[
							'id'=>1,
							'cn'=>'>='
						]
					);
					$copy = [
						'from_chat_id'=>$fromid,
						'message_id'=>$message_id,
						'chat_id'=>$fromid,
					];
					$reply ? $copy['reply_markup'] = json_encode($reply) : false;
					$bot->request('copyMessage',$copy);
					$bot->sendChatAction('typing', $fromid)->setInlineKeyboard($sendConfirm)->sendMessage("Yuborishlikka tayyormi?");
					$db->updateWhere('admins',
						[
							'step'=>'1'
						],
						[
							'fromid'=>$fromid,
							'cn'=>'='
						]
					);
				}
			}
		}else if (isset($update->callback_query)) {
			if (isAdmin($callback_from_id)) {
				if ($data == 'addAdmin') {
					$supperAdmin = mysqli_num_rows(
						$db->selectWhere('admins',[
							[
								'fromid'=>$callback_from_id,
								'cn'=>'='
							],
							[
								'status'=>'supperadmin',
								'cn'=>'='
							]
						])
					);
					if (!$supperAdmin) {
						$bot->request('answerCallbackQuery',[
							'callback_query_id'=>$qid,
							'text'=>"Faqatgina supperadminlar botga admin qo'sha oladi!",
							'show_alert'=>true
						]);
						exit();
					}
					$db->updateWhere('admins',
						[
							'menu'=>'addAdmin',
							'step'=>'0'
						],
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					);
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($cancel)->editMessageText("Admin qo'shish uchun admin qo'shmoqchi bo'lgan odamning habaridan forward qiling, yoki admin qilmoqchi bo'lgan odamning telegram ID raqamini yuboring.\n\nEslatma admin qilmoqchi bo'lgan odamning telegram sozlamarida uzatilgan habarlar hamama uchun ochiq bo'lishligi kerak!", $mid);
					exit();
				}else if($data == 'removeAdmin'){
					$admins = $db->selectAll('admins');
					$adminsList = "Bot adminlari ro'yxati:\n";
					foreach ($admins as $key => $value) {
						$key++;
						$adminsList .= "\n" . $key . ") <a href='tg://user?id=" . $value['fromid'] . "'>" . $value['status'] . "</a> - /remove_admin_" . $value['fromid'];
					} 
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($cancel)->editMessageText($adminsList, $mid);
					exit();
				}else if ($data == 'settingChannel') {
					$channels = $db->withSqlQuery('SELECT * FROM channels LIMIT 1');
					if (!($db->fetch($db->withSqlQuery('SELECT COUNT(id) as total FROM channels LIMIT 2'))['total']>1)) {
						$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($settingChannel)->editMessageText("Botga kanal biriktirilmagan.", $mid);
						exit();
					}
					$channelStatus = mysqli_fetch_assoc($channels);
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($settingChannel)->editMessageText("Nima qilmoqchisiz? Tanlang.\n\nMajburiy azolik hozirgi holatda: " . $channelStatus['target'], $mid);
					exit();
				}else if ($data == 'addChannel') {
					$db->updateWhere('admins',
						[
							'menu'=>'addChannel',
							'step'=>'0'
						],
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					);
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($cancel)->editMessageText("Botga kanal qo'shish uchun kanal user yoki ID sini yuboring, yoki kanaldan forward qiling.\n\nEslatma botga qo'shilayotgan kanalda bot admin bo'lishligi zarur.", $mid);
					exit();
				}else if ($data == 'changeJoinChannel') {
					$db->withSqlQueryWithOutEscapeString("UPDATE channels SET target = IF(target = 'on', 'off', 'on') WHERE name='status'");
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($settingChannel)->editMessageText("Majburiy azolik o'zgartirildi.\nMajburiy azolik hozirgi holatda: " . $db->fetch($db->withSqlQuery('SELECT * FROM channels LIMIT 1'))['target'], $mid);
					exit();
				}else if($data == 'removeChannel'){
					$channels = $db->selectAll('channels');
					if (!(mysqli_num_rows($channels)>1)) {
						$bot->request('answerCallbackQuery',[
							'callback_query_id'=>$qid,
							'text'=>'Botga kanal ulanmagan!',
							'show_alert'=>true
						]);
						exit();
					}
					$channelsList = "Botga biriktirilgan kanallar:\n";
					$i = 0;
					foreach ($channels as $key => $value) {
						$key++;
						if($key == 1) continue; 
						$i++;
						$channelsList .= "\n" . $i . ") " . $value['name'] . " - /remove_channel_" . $value['id'];
					} 
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($cancel)->editMessageText($channelsList, $mid);
					exit();
				}else if ($data == 'sendAd') {
					$db->updateWhere('admins',
						[
							'menu'=>'sendAd',
							'step'=>'0'
						],
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					);
					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$extra = "";
					if ($sendAdConfig['send_confirm'] == 1) {
						$sendAd[3][0]['text'] = "Yuborishni bekor qilish ❌";
						$extra .= "\n\nEslatma hozirda reklama yuborish jarayoni ketmoqda! Reklama borishi kerak bo'lgan userlarni pastdan hozir ham o'zgartirishingiz mumkin. Yuborishni bekor qilish ❌ tugmachasini bosih reklama yuborishni to'xtadi!\n\nYuborib bo'lingan userlar: " . $sendAdConfig['sended_count'];
					}
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin." . $extra, $mid);
					exit();
				}else if ($data == 'toRus') {
					$db->withSqlQuery('UPDATE sendAd SET toRus = IF(toRus = 0, 1, 0);');

					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin.", $mid);
					exit();
				}else if ($data == 'toUs') {
					$db->withSqlQuery('UPDATE sendAd SET toUs = IF(toUs = 0, 1, 0);');

					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin.", $mid);
					exit();
				}else if ($data == 'toUz') {
					$db->withSqlQuery('UPDATE sendAd SET toUz = IF(toUz = 0, 1, 0);');

					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin.", $mid);
					exit();
				}else if ($data == 'toGroup') {
					$db->withSqlQuery('UPDATE sendAd SET toGroup = IF(toGroup = 0, 1, 0);');

					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin.", $mid);
					exit();
				}else if ($data == 'toNotSelectedLang') {
					$db->withSqlQuery('UPDATE sendAd SET toNotSelectedLang = IF(toNotSelectedLang = 0, 1, 0);');

					$sendAdConfig = mysqli_fetch_assoc(
						$db->selectWhere('sendAd',[
							[
								'id'=>1,
								'cn'=>'>='
							]
						])
					);
					$sendAd[0][0]['text'] .= ($sendAdConfig['toRus'] ? '✅' : '❌');
					$sendAd[0][1]['text'] .= ($sendAdConfig['toUs'] ? '✅' : '❌');
					$sendAd[1][0]['text'] .= ($sendAdConfig['toUz'] ? '✅' : '❌');
					$sendAd[1][1]['text'] .= ($sendAdConfig['toNotSelectedLang'] ? '✅' : '❌');
					$sendAd[2][0]['text'] .= ($sendAdConfig['toGroup'] ? '✅' : '❌');
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($sendAd)->editMessageText("Reklama yuborish uchun ixtyoriy habar yuboring. Ayrimlarga yubormoqchi bo'lsangiz, keraksizlarinii pastdagi menyudan o'chirib qo'yishingiz mumkin.", $mid);
					exit();
				}else if ($data == 'sendConfirm') {
					$admin = mysqli_fetch_assoc(
						$db->selectWhere('admins',[
							[
								'fromid'=>$callback_from_id,
								'cn'=>'='
							]
						])
					);
					if ($admin['menu'] == 'sendAd' && $admin['step'] == '1') {
						$db->updateWhere('admins',
							[
								'menu'=>'',
								'step'=>''
							],
							[
								'fromid'=>$callback_from_id,
								'cn'=>'='
							]
						);
						$db->updateWhere('sendAd',
							[
								'send_confirm'=>'1',
								'sending_at'=>date('Y-m-d H:i:s')
							],
							[
								'id'=>1,
								'cn'=>'>='
							]
						);
						$bot->sendChatAction('typing', $callback_from_id)->editMessageText("Reklama yuborish boshlandi. Reklama yuborish bir necha soat vaqt olishi mumkin, ushbu vaqt bot a'zolari soniga bog'liq. Reklama yuborish yakunlanganda bot bu haqida habar beradi. Reklama yuborish boshlangan vaqt: " . date('Y-m-d H:i:s'), $mid);
						exit();
					}
					$bot->request('answerCallbackQuery',[
						'callback_query_id'=>$qid,
						'text'=>'Iltimos reklama yuborishni boshidan boshlang!',
						'show_alert'=>true
					]);
					$db->updateWhere('admins',
						[
							'menu'=>'',
							'step'=>''
						],
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					);
				}else if($data = 'cancelSendAd'){
					$db->updateWhere('sendAd',
						[
							'chat_id'=>'',
							'message_id'=>'',
							'sended_count'=>'0',
							'sended_user_count'=>'0',
							'send_confirm'=>'0',
							'reply_markup'=>json_encode(false),
						],
						[
							'id'=>1,
							'cn'=>'>='
						]
					);

					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($home)->editMessageText('Bosh sahifa.',$mid);
				}
				if($data == 'home' || $data == 'cancelSendAd'){
					$db->updateWhere('admins',
						[
							'menu'=>'',
							'step'=>''
						],
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					);
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyboard($home)->editMessageText('Bosh sahifa.',$mid);
				}
			}
		}
	}
