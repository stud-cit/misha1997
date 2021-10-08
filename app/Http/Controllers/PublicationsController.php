<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Publications;
use App\Models\AuthorsPublications;
use App\Models\Authors;
use App\Models\PublicationType;
use App\Models\Notifications;
use App\Models\Audit;
use App\Models\AuditScopusExport;
use App\Models\Service;

class PublicationsController extends ASUController
{
  protected $scopus_api = 'https://api.elsevier.com/content/';
  protected $scopus_api_key = '01bdf01a22c7c48c8b10fd1dd890e76b';

  // імпорт публікацій Scopus
  function getPublicationsScopus() {
    $access = Service::select('value')->where('key', 'access')->first()->value;
    if($access == 'close') {
      return response('ok', 200);
    }
    $previousDate = date("Y") - 1;
    $resultCount = 0;
    $api = $this->scopus_api . 'search/scopus?';
    if(AuditScopusExport::count() > 0) {
      $api .= 'start=' . AuditScopusExport::select('last_number')->orderBy('created_at')->first()->last_number;
    }
    $api .= '&count=200';
    $api .= '&query=affil%28Sumy+State+University%29+and+pubyear+%3E+' . $previousDate;
    $api .= '&apiKey=' . $this->scopus_api_key;
    $api .= '&field=title,volume,pageRange,doi,publicationName,coverDate,coverDisplayDate,author,subtypeDescription,identifier';

    $data = json_decode(file_get_contents($api), true);

    if(isset($data['search-results']['entry'])) {
      foreach ($data['search-results']['entry'] as $key => $value) {
        $hasAuthors = false;
        $authors = [];
        foreach ($value['author'] as $key => $author) {
          if(Authors::where('scopus_id', $author['authid'])->exists()) {
            $author['scipub_id'] = Authors::select('id')->where('scopus_id', $author['authid'])->first()->id;
            array_push($authors, $author);
            $hasAuthors = true;
          }
        }
        if(!Publications::where('scopus_id', $value['dc:identifier'])->exists() && !Publications::where('title', $value['dc:title'])->exists() && $hasAuthors) {
          $model = new Publications();
          $response = $model->create([
            'title' => $value['dc:title'],
            'science_type_id' => 1,
            'publication_type_id' => $this->getPublicationType($value['subtypeDescription']),
            'year' => date_format(date_create($value['prism:coverDate']), 'Y'),
            'number' => isset($value['prism:volume']) ? $value['prism:volume'] : null,
            'pages' => isset($value['prism:pageRange']) ? $value['prism:pageRange'] : null,
            'name_magazine' => $value['prism:publicationName'],
            'doi' => isset($value['prism:doi']) ? $value['prism:doi'] : null,
            'scopus_id' => $value['dc:identifier']
          ]);
          foreach ($authors as $key => $author) {
            $authorsHasPublicationsModel = new AuthorsPublications();
            $authorsHasPublicationsModel->create([
              'publications_id' => $response['id'],
              'autors_id' => $author['scipub_id']
            ]);
          }
          $resultCount += 1;
        }
      }
    }
    $auditScopusExportModel = new AuditScopusExport();
    $auditScopusExportModel->create([
      'last_number' => $data['search-results']['opensearch:itemsPerPage'],
      'total_count' => $data['search-results']['opensearch:totalResults'],
      'count' => $resultCount
    ]);

    return response('ok', 200);
  }

  function checkAffiliation($affiliation) {
    $res = [];
    foreach ($affiliation as $value) {
      if($value['afid'] == '60016511') {
        array_push($res, $value['afid']);
      }
    }
    return count($res) > 0 ? true : false;
  }

  function getPublicationsScopusUser(Request $request) {
    if($request->session()->get('person')['scopus_id']) {
      $data = json_decode(file_get_contents($this->scopus_api . 'search/scopus?query=au-id(' . $request->session()->get('person')['scopus_id'] . ')&field=affiliation&apiKey=' . $this->scopus_api_key), true);
      foreach ($data['search-results']['entry'] as $key => $value) {
        if(isset($value['affiliation']) && $this->checkAffiliation($value['affiliation'])) {
          $publicationData = json_decode(file_get_contents($value['prism:url'] . '?field=title,volume,pageRange,doi,publicationName,identifier,subtypeDescription,language,coverDate,head,authors&httpAccept=application/json&apiKey=' . $this->scopus_api_key), true);

          $head = $publicationData['abstracts-retrieval-response']['item']['bibrecord']['head'];
          $coredata = $publicationData['abstracts-retrieval-response']['coredata'];

          $hasAuthors = false;
          $authors = [];

          foreach ($publicationData['abstracts-retrieval-response']['authors']['author'] as $key => $author) {
            if(Authors::where('scopus_id', $author['@auid'])->exists()) {
              $author['scipub_id'] = Authors::select('id')->where('scopus_id', $author['@auid'])->first()->id;
              array_push($authors, $author);
              $hasAuthors = true;
            }
          }

          if(!Publications::where('scopus_id', $coredata['dc:identifier'])->exists() && !Publications::where('title', $coredata['dc:title'])->exists() && $hasAuthors) {
            $model = new Publications();
            $response = $model->create([
              "title" => $coredata['dc:title'],
              "science_type_id" => 1,
              "publication_type_id" => $this->getPublicationType($coredata['subtypeDescription']),
              "year" => isset($head['source']['publicationdate']['year']) ? $head['source']['publicationdate']['year'] : null,
              "number" => isset($coredata['prism:volume']) ? $coredata['prism:volume'] : null,
              "pages" => isset($coredata['prism:pageRange']) ? $coredata['prism:pageRange'] : null,
              "name_magazine" => isset($coredata['prism:publicationName']) ? $coredata['prism:publicationName'] : null,
              "doi" => isset($coredata['prism:doi']) ? $coredata['prism:doi'] : null,
              'scopus_id' => $coredata['dc:identifier']
            ]);

            foreach ($authors as $key => $author) {
              $authorsHasPublicationsModel = new AuthorsPublications();
              $authorsHasPublicationsModel->create([
                'publications_id' => $response['id'],
                'autors_id' => $author['scipub_id']
              ]);
            }
          }
        }
      }
    }
    return response('ok', 200);
  }

  // визначення типу публікації відповідно даних Scopus
  function getPublicationType($type) {
    switch ($type) {
      case 'Article':
        return 3;
        break;
      case 'Review':
        return 3;
        break;
      case 'Conference Paper':
        return 2;
        break;
      case 'Book':
        return 4;
        break;
      case 'Editorial':
        return 9;
        break;
      case 'Note':
        return 9;
        break;
      case 'Book Chapper':
        return 8;
        break;
      default:
        return 3;
        break;
    }
  }

  function get(Request $request, $author_id = null) {

    $model = Publications::with('allAuthors.author', 'publicationType')->where('status_id', $request->status_id);

    $allDivisions = $this->getAllDivision();

    $model->orderBy($request->sortBy, $request->sortOrder == 1 ? 'asc' : 'desc');
    
    if(!$author_id) {
      $divisions = $this->getDivisions();
      if($request->session()->get('person')['roles_id'] == 2) {
          $departments_id = [$request->session()->get('person')['department_code']];
          foreach($divisions->original['department'] as $k2 => $v2) {
              if ($v2['ID_PAR'] == $request->session()->get('person')['department_code']) {
                  array_push($departments_id, $v2['ID_DIV']);
              }
          }
          $model->whereHas('allAuthors.author', function($q) use ($departments_id) {
              $q->whereIn('department_code', $departments_id);
          });
      } else {
          if($request->session()->get('person')['roles_id'] == 3) {
              $departments_id = [$request->session()->get('person')['faculty_code']];
              foreach($divisions->original['department'] as $k2 => $v2) {
                  if ($v2['ID_PAR'] == $request->session()->get('person')['faculty_code']) {
                      array_push($departments_id, $v2['ID_DIV']);
                  }
              }
              $model->whereHas('allAuthors.author', function($q) use ($departments_id) {
                  $q->whereIn('faculty_code', $departments_id);
              });
          }
      }
    }

    // Публікації автора
    if($author_id) {
      $model->whereHas('allAuthors', function($query) use ($request, $author_id) {
          $query->whereHas('author', function($query) use ($request, $author_id) {
              $query->where('id', $author_id);
          });
          if($request->withSupervisor == "false") {
              $query->where('supervisor', '!=', 1);
          }
      });
    }

    // публікація scopus
    if($request->scopus == "true") {
      $model->whereNotNull('scopus_id');
    }

    // Назва публікації
    if($request->title) {
      $model->where('title', 'like', "%".$request->title."%");
    }

    // ПІБ автора
    if($request->name) {
      $model->whereHas('allAuthors.author', function($q) use ($request) {
          $q->where('name', 'like', "%".$request->name."%");
      });
    }

    // Інститут / факультет
    if(isset($request->department_code) || isset($request->faculty_code)) {
      $divisions = $this->getDivisions();
      if(isset($request->department_code) && count($request->department_code) > 0) {
        $departments_id = $request->department_code;
        foreach($divisions->original['department'] as $v2) {
            if (in_array($v2['ID_PAR'], $request->department_code)) {
                array_push($departments_id, $v2['ID_DIV']);
            }
        }
        $model->whereHas('allAuthors.author', function($q) use ($departments_id) {
            $q->whereIn('department_code', $departments_id);
        });
      } else {
      // Кафедра
        if(isset($request->faculty_code) && count($request->faculty_code) > 0) {
          $departments_id = $request->faculty_code;
          foreach($divisions->original['department'] as $v2) {
              if (in_array($v2['ID_PAR'], $request->faculty_code)) {
                  array_push($departments_id, $v2['ID_DIV']);
              }
          }
          $model->whereHas('allAuthors.author', function($q) use ($departments_id) {
              $q->whereIn('faculty_code', $departments_id);
          });
        }
      }
    }

    // Посада
    if(isset($request->position)) {
      $model->where(function($queryModel) use($request) {
        foreach($request->position as $value) {
          switch ($value) {
            case 1: // Аспіранти
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('categ_1', 2);
              });
              break;
            case 2: // Викладачі
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('categ_2', 2);
              });
              break;
            case 3: // Докторанти
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('kod_level', 9);
              });
              break;
            case 4: // Зовнішні співавтори
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('job_type_id', '!=', 5)->where('job_type_id', '!=', 6);
              });
              break;
            case 5: // Іноземці
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('country', '!=', 'Україна');
              });
              break;
            case 6: // Менеджери
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('categ_2', 3);
              });
              break;
            case 7: // Співробітники
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('categ_2', 1);
              });
              break;
            case 8: // Студенти, випускники
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('categ_1', 1)->orWhere('categ_1', 3);
              });
              break;
            case 9: // СумДУ
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('job_type_id', 5);
              });
              break;
            case 10: // СумДУ (не працює)
              $queryModel->orWhereHas('allAuthors.author', function($query) {
                $query->where('job_type_id', 6);
              });
              break;
          }
        }
      });
    }

    // БД Scopus/WoS
    if($request->science_type_id) {
      $model->whereIn('science_type_id', $request->science_type_id);
    }

    // Рік видання
    if($request->years) {
        $model->whereIn('year', $request->years);
    }

    // Країна видання
    if($request->country) {
      if($request->country == 'ОЕСР') {
        $oecr = [
          'Австралія', 
          'Австрія', 
          'Бельгія', 
          'Великобританія', 
          'Греція', 
          'Данія', 
          'Естонія', 
          'Ізраїль', 
          'Іспанія', 
          'Ісландія', 
          'Ірландія', 
          'Італія', 
          'Канада', 
          'Корея', 
          'Латвія', 
          'Люксембург', 
          'Мексика', 
          'Німеччина', 
          'Норвегія', 
          'Нова Зеландія', 
          'Нідерланди', 
          'Польща', 
          'Португалія', 
          'Словаччина', 
          'Словенія', 
          'США', 
          'Туреччина', 
          'Угорщина', 
          'Фінляндія', 
          'Франція', 
          'Чехія', 
          'Чилі', 
          'Швейцарія', 
          'Швеція', 
          'Японія'
        ];
        $model->whereIn('country', $oecr);
      } else {
        $model->where('country', $request->country);
      }
    }

    // Вид публікації
    if($request->publication_type_id) {
        $model->whereIn('publication_type_id', $request->publication_type_id);
    }

    // Публікації Scopus
    if(isset($request->isScopus)) {
      $model->whereIn('science_type_id', [1, 3])->where(function($queryModel) use($request) {
        foreach ($request->isScopus as $value) {
          switch ($value) {
            case 1:
              $queryModel->orWhere(function($query) {
                $query->whereNull('scopus_id');
              });
              break;
            case 2:
              $queryModel->orWhere(function($query) {
                $query->whereNotNull('scopus_id');
              });
              break;
            case 3:
              $queryModel->orWhere(function($query) {
                $query->whereNotNull('scopus_id')->where('verification', 1);
              });
              break;
            case 4:
              $queryModel->orWhere(function($query) {
                $query->whereNotNull('scopus_id')->where('verification', 0);
              });
              break;
          }
        }
      });
    }

    // Під керівництвом
    if($request->supervisor == "true") {
      $model->whereHas('allAuthors', function($q) {
          $q->where('supervisor', 1);
      });
    }

    // Публікації які не враховані в рейтингу попереднього року
    if($request->notPreviousYear == "true") {
      $model->where('not_previous_year', 1);
    }

    // Публікації які не враховані в рейтингу цього року
    if($request->notThisYear == "true") {
      $model->where('not_this_year', 1);
    }

    $publications = $model->paginate($request->size);

    foreach ($publications as $publication) {
      foreach ($publication->allAuthors as $value) {
        $kod_div = $value->author['department_code'] ? $value->author['department_code'] : $value->author['faculty_code'];
        $sectionId = array_search($kod_div, array_column($allDivisions, 'ID_DIV'));
        $departmentId = array_search($allDivisions[$sectionId]['ID_PAR'], array_column($allDivisions, 'ID_DIV'));
        $facultyId = array_search($allDivisions[$departmentId]['ID_PAR'], array_column($allDivisions, 'ID_DIV'));
        if(!$departmentId) {
            $departmentId = $sectionId;
            $sectionId = null;
        }
        if(!$facultyId) {
            $facultyId = $departmentId;
            $departmentId = $sectionId;
            $sectionId = null;
        }
        $value->author->department = $departmentId ? $allDivisions[$departmentId]['NAME_DIV'] : null;
        $value->author->faculty = $facultyId ? $allDivisions[$facultyId]['NAME_DIV'] : null;
      }
    }

    $last_date_export = AuditScopusExport::select('created_at')->orderBy('created_at', 'desc')->first();

    return response()->json([
      "currentPage" => $publications->currentPage(),
      "firstItem" => $publications->firstItem(),
      "count" => $publications->total(),
      "publications" => $publications,
      "last_date_export" => $last_date_export
    ]);
  }

  function getId($id) {
    $data = Publications::with(
      'supervisor.author',
      'publicationType', 
      'scienceType', 
      'authors.author', 
      'authors.supervisor', 
      'publicationAdd', 
      'publicationEdit', 
      'patentType'
    )->find($id);
    return response()->json($data);
  }

  // додавання публікації
  function post(Request $request) {
    $modelPublications = new Publications();
    $dataPublications = $request->all();
    $dataPublications['publication_type_id'] = $dataPublications['publication_type']['id'];
    $dataPublications['add_user_id'] = $request->session()->get('person')['id'];
    $response = $modelPublications->create($dataPublications);

    foreach ($request->authors as $key => $value) {
        $authorsPublications = new AuthorsPublications;
        $authorsPublications->autors_id = $value['id'];
        $authorsPublications->publications_id = $response->id;
        if(isset($value['supervisor'])) {
          $authorsPublications->student_supervisor = $value['supervisor']['id'];
        }
        $authorsPublications->save();
        if($value['id'] != $request->session()->get('person')['id']) {
            Notifications::create([
                "autors_id" => $value['id'],
                "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> додав публікацію <a href=\"/publication/".$response['id']."\">\"".$response['title']."\"</a> і відзначив Вас співавтором публікації."
            ]);
        }
    }
    if($dataPublications['supervisor']) {
        $authorsPublications = new AuthorsPublications;
        $authorsPublications->autors_id = $dataPublications['supervisor']['id'];
        $authorsPublications->publications_id = $response->id;
        $authorsPublications->supervisor = true;
        $authorsPublications->save();
        if($dataPublications['supervisor']['id'] != $request->session()->get('person')['id']) {
            Notifications::create([
                "autors_id" => $dataPublications['supervisor']['id'],
                "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> додав публікацію <a href=\"/publication/".$response['id']."\">\"".$response['title']."\"</a> і відзначив Вас співавтором публікації."
            ]);
        }
    }

    Audit::create([
        "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> додав публікацію <a href=\"/publication/".$response['id']."\">\"".$response['title']."\"</a>."
    ]);

    return response('ok', 200);
  }

  // оновлення публікації
  function updatePublication(Request $request, $id) {

      $data = $request->all();
      $model = Publications::with('allAuthors', 'publicationType')->find($id);
      $data['edit_user_id'] = $request->session()->get('person')['id'];
      $data['verification'] = true;
      $notificationText = "";

      // check authors old or new
      $oldAuthors = [];
      foreach ($model->allAuthors as $key => $value) {
          if($value['supervisor'] == 0) {
              array_push($oldAuthors, $value['autors_id']);
          }
      }

      foreach ($data['authors'] as $key => $value) {
          if(AuthorsPublications::where('autors_id', $value['id'])->where('publications_id', $id)->exists()) {
              unset($oldAuthors[array_search($value['id'], $oldAuthors)]);
          } else {
              $authorsPublications = new AuthorsPublications;
              $authorsPublications->autors_id = $value['id'];
              $authorsPublications->publications_id = $id;
              if(isset($value['supervisor'])) {
                $authorsPublications->student_supervisor = $value['supervisor']['id'];
              }
              $authorsPublications->save();
              $notificationText .= "додано автора: <a href=\"/user/". $value['id'] ."\">" . $value['name'] . "</a>;<br>";
          }
      }

      foreach ($oldAuthors as $key => $value) {
          AuthorsPublications::where('autors_id', $value)->where('publications_id', $id)->delete();
          $notificationText .= "видалено автора: " . $this->test($value) . ";<br>";
      }
      // end check

      // check supervisor
      if($data['supervisor']) {
          if(AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->exists()) {

              if(!AuthorsPublications::where('autors_id', $data['supervisor']['id'])->where('publications_id', $id)->where('supervisor', 1)->exists()) {
                  AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->update([
                      "autors_id" => $data['supervisor']['id']
                  ]);

                  $notificationText .= "змінено керівника: <a href=\"/user/". $data['old_supervisor']['id'] ."\">" . $data['old_supervisor']['name'] . "</a> на <a href=\"/user/". $data['supervisor']['id'] ."\">" . $data['supervisor']['name'] . "</a>;<br>";
              }
          } else {
              $authorsPublications = new AuthorsPublications;
              $authorsPublications->autors_id = $data['supervisor']['id'];
              $authorsPublications->publications_id = $id;
              $authorsPublications->supervisor = 1;
              $authorsPublications->save();
              $notificationText .= "додано керівника: <a href=\"/user/". $data['supervisor']['id'] ."\">" . $data['supervisor']['name'] . "</a>;<br>";
          }
      } else {
          if($data['old_supervisor']) {
              AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->delete();
              $notificationText .= "видалено керівника: <a href=\"/user/". $data['old_supervisor']['id'] ."\">" . $data['old_supervisor']['name'] . "</a>;<br>";
          }
      }
      // end check



      // перевірка полів
      // Назва
      $notificationText .= $this->notification($data, $model, "title", "назву");

      // БД Scopus\WoS
      $science_type = [
          "1" => "Scopus",
          "2" => "WoS",
          "3" => "Scopus та WoS"
      ];
      $notificationText .= $this->notification($data, $model, "science_type_id", "базу даних", $science_type);

      // тип публікації
      if($data['publication_type']['id'] != $model->publication_type_id) {
          $data['publication_type_id'] = $data['publication_type']['id'];
          $notificationText .= "змінено тип публікації: " . $model->publicationType['title'] . " на " . $data['publication_type']['title'] . ";<br>";
      }

      $notificationText .= $this->notification($data, $model, "snip", "SNIP");
      $notificationText .= $this->notification($data, $model, "quartil_scopus", "квартиль Scopus");
      $notificationText .= $this->notification($data, $model, "quartil_wos", "квартиль WoS");
      $notificationText .= $this->notification($data, $model, "impact_factor", "Impact Factor");
      $notificationText .= $this->notification($data, $model, "year", "рік видання");
      $notificationText .= $this->notification($data, $model, "number", "номер (том)");
      $notificationText .= $this->notification($data, $model, "pages", "сторінки");
      $notificationText .= $this->notification($data, $model, "country", "країна");
      $notificationText .= $this->notification($data, $model, "number_volumes", "кількість томів");
      $notificationText .= $this->notification($data, $model, "by_editing", "за редакцією");
      $notificationText .= $this->notification($data, $model, "city", "місто");
      $notificationText .= $this->notification($data, $model, "editor_name", "назву редакції");
      $notificationText .= $this->notification($data, $model, "number_certificate", "номер");
      $notificationText .= $this->notification($data, $model, "applicant", "заявника");
      $notificationText .= $this->notification($data, $model, "date_application", "дата подачі");
      $notificationText .= $this->notification($data, $model, "date_publication", "дата публікації");
      $commerc_university = [
          "1" => "Так",
          "0" => "Ні"
      ];
      $notificationText .= $this->notification($data, $model, "commerc_university", "комерціалізовано університетом", $commerc_university);
      $commerc_employees = [
          "1" => "Так",
          "0" => "Ні"
      ];
      $notificationText .= $this->notification($data, $model, "commerc_employees", "Комерціалізовано штатними співробітниками університету", $commerc_employees);
      $access_mode = [
          "1" => "Відкритий",
          "0" => "Закрити"
      ];
      $notificationText .= $this->notification($data, $model, "access_mode", "Режим доступу", $access_mode);
      $notificationText .= $this->notification($data, $model, "application_number", "№ заявки");
      $notificationText .= $this->notification($data, $model, "newsletter_number", "№ бюлетеня");
      $notificationText .= $this->notification($data, $model, "name_conference", "Назва конференції");
      $notificationText .= $this->notification($data, $model, "url", "Посилання");
      $notificationText .= $this->notification($data, $model, "name_magazine", "Назва журналу");
      $notificationText .= $this->notification($data, $model, "doi", "DOI");
      $nature_index = [
          "1" => "Так",
          "2" => "Ні"
      ];
      $notificationText .= $this->notification($data, $model, "nature_index", "Natire Index", $nature_index);
      $notificationText .= $this->notification($data, $model, "nature_science", "журнал");

      // Підбаза WoS
      $sub_db_scie = [
          "1" => "Так",
          "0" => "Ні",
      ];

      $sub_db_ssci = [
          "1" => "Так",
          "0" => "Ні",
      ];
      $notificationText .= $this->notification($data, $model, "sub_db_scie", "Підбаза WoS - SCIE", $sub_db_scie);
      $notificationText .= $this->notification($data, $model, "sub_db_ssci", "підбазу WoS - SSCI", $sub_db_ssci);

      if($notificationText != "") {
          $notificationText = "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> вніс наступні зміни в публікацію <a href=\"/publication/". $id ."\">" . $model->title . "</a>:<br>" . $notificationText;
          foreach ($data['authors'] as $k => $author) {
              if($author['id'] != $request->session()->get('person')['id']) {
                  Notifications::create([
                      "autors_id" => $author['id'],
                      "text" => $notificationText
                  ]);
              }
          }
          Audit::create([
              "text" => $notificationText
          ]);
      }
      $model->update($data);
  }

  function test($id) {
      $result = Authors::select('name')->find($id);
      return $result['name'];
  }

  function notification($data, $model, $key, $text, $arr = null) {
      if($arr) {
          if($data[$key] && !$model->$key) {
              return "додано ".$text.": " . $arr[$data[$key]] . ";<br>";
          }
          if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
              return "змінено ".$text.": " . $arr[$model->$key] . " на " . $arr[$data[$key]] . ";<br>";
          }
          if(!$data[$key] && $model->$key) {
              return "видалено ".$text.";<br>";
          }
      } else {
          if($data[$key] && !$model->$key) {
              return "додано ".$text.": " . $data[$key] . ";<br>";
          }
          if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
              return "змінено ".$text.": " . $model->$key . " на " . $data[$key] . ";<br>";
          }
          if(!$data[$key] && $model->$key) {
              return "видалено ".$text.";<br>";
          }
      }
  }

  // видалення публікацій
  function deletePublications(Request $request) {
    $model = Publications::with('allAuthors.author')->whereIn('id', $request->publications)->get();
    foreach ($model as $key => $publication) {
        foreach ($publication['allAuthors'] as $k => $author) {
            if($author['author']['id'] != $request->session()->get('person')['id']) {
                Notifications::create([
                    "autors_id" => $author['author']['id'],
                    "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> видалив Вашу публікацію \"".$publication['title']."\"."
                ]);
            }
        }
    }
    Publications::whereIn('id', $request->publications)->update([
      'status_id' => 2
    ]);
    Audit::create([
        "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> видалив публікацію \"<a href=\"/publication/" . $publication['id'] . "\">".$publication['title']."</a>\"."
    ]);
    return response('ok', 200);
  }

  // відновлення публікацій
  function restorePublications(Request $request) {
    $model = Publications::with('allAuthors.author')->whereIn('id', $request->publications)->get();

    foreach ($model as $key => $publication) {
        foreach ($publication['allAuthors'] as $k => $author) {
            if($author['author']['id'] != $request->session()->get('person')['id']) {
                Notifications::create([
                    "autors_id" => $author['author']['id'],
                    "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> відновив Вашу публікацію \"<a href=\"/publication/" . $publication['id'] . "\">".$publication['title']."</a>\"."
                ]);
            }
        }
    }
    Publications::whereIn('id', $request->publications)->update([
      'status_id' => 1
    ]);
    Audit::create([
        "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> відновив публікацію \"<a href=\"/publication/" . $publication['id'] . "\">".$publication['title']."</a>\"."
    ]);
    return response('ok', 200);
  }

  function getAllNames() {
    $data = Publications::select('title', 'publication_type_id')->where('status_id', 1)->with('publicationType')->get();
    return response()->json($data);
  }
}
