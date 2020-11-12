<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class UsuarioModel
{
    private $db;
    private $table = 'tusuarios';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function ObtenerTodos()
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table");
			$stm->execute();
            
			$this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            
            return $this->response->result;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
    public function ObtenerPorEnviar()
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE enviado='0' AND email!=''");
			$stm->execute();
            
			$this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            
            return $this->response->result;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }    
    public function ObtenerEmail($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT email FROM $this->table WHERE usuario = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();
            
            return $this->response->result;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }
    public function ActualizarEmail($data)
    {
        try 
        {
            
            $sql = "UPDATE $this->table SET 
                        email  = ?
                        WHERE usuario = ?";
                
            $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['sEmail'], 
                            $data['sUsuario']
                        )
            );
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
    
    public function EliminarUsuario($id)
    {
		try 
		{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE usuario = ?");			          

			$stm->execute(array($id));
            
			$this->response->setResponse(true);
            return $this->response->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }

    public function EnviarEmailUsuario($data)
    {
		try 
		{
			$subject = "Sugerencias para obtener SIGNALS";
            $body = "Buenas noches ".$data['sUsuario'].", ";
            $body .= "El E-mail indicado es: ".$data['sEmail'].'<br />';
            $to = $data['sEmail'];
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";   
            $sendmail = mail($to, $subject, $body, $headers);
            if ($sendmail){
                $this->response->setResponse(true);
                $sql = "UPDATE $this->table SET 
                        enviado  = ?
                        WHERE usuario = ?";                
                $this->db->prepare($sql)
                        ->execute(
                            array(
                                "1", 
                                $data['sUsuario']
                            )
                );
            }
            else
                $this->response->setResponse(false);            
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
        }
        return $this->response->response;
    }
    
}