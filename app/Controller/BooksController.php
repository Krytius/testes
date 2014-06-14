<?php

/**
 * Classe de Controle dos books
 */
class BooksController extends AppController
{

    public $name = 'Books';
    public $uses = array('Books');
    
    /**
     * Faz busca total e com id
     * @throws Exception
     */
    public function index()
    {
        try {
            $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
            $this->autoLayout = false;
            $resposta = array();

            if ($id) {
                $books = $this->Books->findById($id);
                if (!$books) {
                    throw new Exception;
                }

                $resposta = array(
                    'title'     => $books['Books']['title'],
                    'author'    => $books['Books']['author'],
                    'isbn'      => $books['Books']['isbn'],
                    'publisher' => $books['Books']['publisher'],
                );
            } else {
                $books = $this->Books->find('all');

                if (!$books) {
                    throw new Exception;
                }

                foreach ($books as $key => $val) {
                    array_push($resposta, array(
                        'title'  => $val['Books']['title'],
                        'author' => $val['Books']['author']
                    ));
                }
            }
        } catch (Exception $e) {
            $resposta = array(
                'error' => true,
                'msg'   => 'Não há registro para id informado.'
            );
        }

        $this->set('books', json_encode($resposta));
    }
    
    /**
     * Tipos de busca diferentes na tabela de books
     * url : /books/author:R.+Bradbury2
     * url : /books/title:Fahrenheit+451
     * url : /books/title:Fahrenheit+451/author:R.+Bradbury
     * @return json
     */
    public function busca()
    {
        try {
            $this->autoLayout = false;
            $resposta = array();
            $author = isset($this->passedArgs['author']) ? $this->passedArgs['author'] : '';
            $title = isset($this->passedArgs['title']) ? $this->passedArgs['title'] : '';

            if ($author && $title) {
                $resposta = $this->titleAuthor($author, $title);
            } else if ($author) {
                $resposta = $this->author($author);
            } else if ($title) {
                $resposta = $this->title($title);
            }
        } catch (Exception $e) {
            $resposta = array(
                'error' => true,
                'msg'   => 'Não há registro para id informado.'
            );
        }

        $this->set('books', json_encode($resposta));
    }
    
    /**
     * Consulta pelo title e author
     * @param string $author
     * @param string $title
     * @return array
     * @throws Exception
     */
    private function titleAuthor($author, $title)
    {
        try {
            $resposta = array();
            $condicao = array(
                'conditions' => array(
                    'Books.author LIKE' => "%{$author}%",
                    'Books.title LIKE'  => "%{$title}%"
                )
            );
            $books = $this->Books->find('all', $condicao);

            if (!$books) {
                throw new Exception;
            }

            foreach ($books as $key => $val) {
                array_push($resposta, array(
                    'title'     => $val['Books']['title'],
                    'author'    => $val['Books']['author'],
                    'isbn'      => $val['Books']['isbn'],
                    'publisher' => $val['Books']['publisher']
                ));
            }
        } catch (Exception $e) {
            $resposta = array(
                'error' => true,
                'msg'   => 'Não há registro para id informado.'
            );
        }

        return $resposta;
    }
    
    /**
     * Consulta pelo title
     * @param string $title
     * @return array
     * @throws Exception
     */
    private function title($title)
    {
        try {
            $resposta = array();
            $books = $this->Books->find('all', array('conditions' => array('Books.title LIKE' => "%{$title}%")));

            if (!$books) {
                throw new Exception;
            }

            foreach ($books as $key => $val) {
                array_push($resposta, array(
                    'title'     => $val['Books']['title'],
                    'author'    => $val['Books']['author'],
                    'isbn'      => $val['Books']['isbn'],
                    'publisher' => $val['Books']['publisher']
                ));
            }
        } catch (Exception $e) {
            $resposta = array(
                'error' => true,
                'msg'   => 'Não há registro para id informado.'
            );
        }

        return $resposta;
    }
    
    /**
     * Consulta pelo author
     * @param string $author
     * @return array
     * @throws Exception
     */
    private function author($author)
    {
        try {
            $resposta = array();
            $books = $this->Books->find('all', array('conditions' => array('Books.author LIKE' => "%{$author}%")));

            if (!$books) {
                throw new Exception;
            }

            foreach ($books as $key => $val) {
                array_push($resposta, array(
                    'title'     => $val['Books']['title'],
                    'author'    => $val['Books']['author'],
                    'isbn'      => $val['Books']['isbn'],
                    'publisher' => $val['Books']['publisher']
                ));
            }
        } catch (Exception $e) {
            $resposta = array(
                'error' => true,
                'msg'   => 'Não há registro para id informado.'
            );
        }
        return $resposta;
    }

}
