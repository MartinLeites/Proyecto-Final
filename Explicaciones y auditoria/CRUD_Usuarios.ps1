# CRUD_Usuarios.ps1
# Script para administración de usuarios en Windows Server

param(
    [string]$Action,
    [string]$Username,
    [string]$Password,
    [string]$FullName,
    [string]$Description,
    [string]$Group,
    [switch]$ListAll
)

function Show-Menu {
    Clear-Host
    Write-Host "==========================================" -ForegroundColor Cyan
    Write-Host "    ADMINISTRACION DE USUARIOS - CRUD" -ForegroundColor Cyan
    Write-Host "==========================================" -ForegroundColor Cyan
    Write-Host "1. Crear usuario" -ForegroundColor Green
    Write-Host "2. Listar usuarios" -ForegroundColor Yellow
    Write-Host "3. Buscar usuario" -ForegroundColor Yellow
    Write-Host "4. Actualizar usuario" -ForegroundColor Blue
    Write-Host "5. Eliminar usuario" -ForegroundColor Red
    Write-Host "6. Agregar usuario a grupo" -ForegroundColor Magenta
    Write-Host "7. Salir" -ForegroundColor Gray
    Write-Host "==========================================" -ForegroundColor Cyan
}

function Create-User {
    param(
        [string]$Username,
        [string]$Password,
        [string]$FullName,
        [string]$Description
    )
    
    try {
        # Verificar si el usuario ya existe
        if (Get-LocalUser -Name $Username -ErrorAction SilentlyContinue) {
            Write-Host "Error: El usuario '$Username' ya existe." -ForegroundColor Red
            return $false
        }
        
        # Crear el usuario
        $SecurePassword = ConvertTo-SecureString -String $Password -AsPlainText -Force
        $UserParams = @{
            Name = $Username
            Password = $SecurePassword
            FullName = $FullName
            Description = $Description
        }
        
        New-LocalUser @UserParams
        Write-Host "Usuario '$Username' creado exitosamente." -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "Error al crear usuario: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

function List-Users {
    param([string]$Filter = "*")
    
    try {
        $users = Get-LocalUser -Name $Filter | Select-Object Name, FullName, Description, Enabled, LastLogon
        return $users
    }
    catch {
        Write-Host "Error al listar usuarios: $($_.Exception.Message)" -ForegroundColor Red
        return $null
    }
}

function Get-UserDetails {
    param([string]$Username)
    
    try {
        $user = Get-LocalUser -Name $Username -ErrorAction Stop
        $userGroups = Get-LocalGroup | Where-Object {
            (Get-LocalGroupMember -Group $_.Name -ErrorAction SilentlyContinue | 
             Where-Object Name -like "*$Username*") -ne $null
        }
        
        return @{
            User = $user
            Groups = $userGroups
        }
    }
    catch {
        Write-Host "Usuario '$Username' no encontrado." -ForegroundColor Red
        return $null
    }
}

function Update-User {
    param(
        [string]$Username,
        [string]$NewFullName,
        [string]$NewDescription,
        [string]$NewPassword
    )
    
    try {
        $user = Get-LocalUser -Name $Username -ErrorAction Stop
        
        if ($NewFullName) {
            Set-LocalUser -Name $Username -FullName $NewFullName
        }
        
        if ($NewDescription) {
            Set-LocalUser -Name $Username -Description $NewDescription
        }
        
        if ($NewPassword) {
            $SecurePassword = ConvertTo-SecureString -String $NewPassword -AsPlainText -Force
            Set-LocalUser -Name $Username -Password $SecurePassword
        }
        
        Write-Host "Usuario '$Username' actualizado exitosamente." -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "Error al actualizar usuario: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

function Remove-User {
    param([string]$Username)
    
    try {
        $user = Get-LocalUser -Name $Username -ErrorAction Stop
        
        # Confirmar eliminación
        $confirmation = Read-Host "¿Está seguro de eliminar el usuario '$Username'? (S/N)"
        if ($confirmation -eq 'S' -or $confirmation -eq 's') {
            Remove-LocalUser -Name $Username
            Write-Host "Usuario '$Username' eliminado exitosamente." -ForegroundColor Green
            return $true
        } else {
            Write-Host "Operación cancelada." -ForegroundColor Yellow
            return $false
        }
    }
    catch {
        Write-Host "Error al eliminar usuario: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

function Add-UserToGroup {
    param(
        [string]$Username,
        [string]$GroupName
    )
    
    try {
        # Verificar que el usuario existe
        Get-LocalUser -Name $Username -ErrorAction Stop | Out-Null
        
        # Verificar que el grupo existe
        Get-LocalGroup -Name $GroupName -ErrorAction Stop | Out-Null
        
        # Agregar usuario al grupo
        Add-LocalGroupMember -Group $GroupName -Member $Username
        Write-Host "Usuario '$Username' agregado al grupo '$GroupName'." -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "Error al agregar usuario al grupo: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

# Modo interactivo
if (-not $Action) {
    do {
        Show-Menu
        $choice = Read-Host "`nSeleccione una opción"
        
        switch ($choice) {
            "1" {
                Write-Host "`n--- CREAR USUARIO ---" -ForegroundColor Green
                $Username = Read-Host "Nombre de usuario"
                $Password = Read-Host "Contraseña" -AsSecureString
                $BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($Password)
                $PlainPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
                $FullName = Read-Host "Nombre completo"
                $Description = Read-Host "Descripción"
                
                Create-User -Username $Username -Password $PlainPassword -FullName $FullName -Description $Description
                Pause
            }
            "2" {
                Write-Host "`n--- LISTA DE USUARIOS ---" -ForegroundColor Yellow
                $users = List-Users
                if ($users) {
                    $users | Format-Table -AutoSize
                }
                Pause
            }
            "3" {
                Write-Host "`n--- BUSCAR USUARIO ---" -ForegroundColor Yellow
                $Username = Read-Host "Nombre de usuario a buscar"
                $userDetails = Get-UserDetails -Username $Username
                if ($userDetails) {
                    Write-Host "`nInformación del usuario:" -ForegroundColor Cyan
                    $userDetails.User | Format-List
                    Write-Host "Grupos:" -ForegroundColor Cyan
                    $userDetails.Groups | Format-Table -AutoSize
                }
                Pause
            }
            "4" {
                Write-Host "`n--- ACTUALIZAR USUARIO ---" -ForegroundColor Blue
                $Username = Read-Host "Nombre de usuario a actualizar"
                $NewFullName = Read-Host "Nuevo nombre completo (Enter para mantener actual)"
                $NewDescription = Read-Host "Nueva descripción (Enter para mantener actual)"
                $NewPassword = Read-Host "Nueva contraseña (Enter para mantener actual)" -AsSecureString
                
                if ($NewPassword.Length -gt 0) {
                    $BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($NewPassword)
                    $PlainNewPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
                } else {
                    $PlainNewPassword = ""
                }
                
                Update-User -Username $Username -NewFullName $NewFullName -NewDescription $NewDescription -NewPassword $PlainNewPassword
                Pause
            }
            "5" {
                Write-Host "`n--- ELIMINAR USUARIO ---" -ForegroundColor Red
                $Username = Read-Host "Nombre de usuario a eliminar"
                Remove-User -Username $Username
                Pause
            }
            "6" {
                Write-Host "`n--- AGREGAR USUARIO A GRUPO ---" -ForegroundColor Magenta
                $Username = Read-Host "Nombre de usuario"
                $GroupName = Read-Host "Nombre del grupo"
                Add-UserToGroup -Username $Username -GroupName $GroupName
                Pause
            }
            "7" {
                Write-Host "Saliendo..." -ForegroundColor Gray
                exit
            }
            default {
                Write-Host "Opción no válida. Presione cualquier tecla para continuar." -ForegroundColor Red
                Pause
            }
        }
    } while ($true)
}

# Modo por línea de comandos
else {
    switch ($Action.ToLower()) {
        "create" {
            if (-not $Username -or -not $Password) {
                Write-Host "Error: Se requieren -Username y -Password para crear usuario." -ForegroundColor Red
                exit 1
            }
            Create-User -Username $Username -Password $Password -FullName $FullName -Description $Description
        }
        "list" {
            if ($ListAll) {
                List-Users
            } elseif ($Username) {
                Get-UserDetails -Username $Username
            } else {
                List-Users
            }
        }
        "update" {
            if (-not $Username) {
                Write-Host "Error: Se requiere -Username para actualizar usuario." -ForegroundColor Red
                exit 1
            }
            Update-User -Username $Username -NewFullName $FullName -NewDescription $Description -NewPassword $Password
        }
        "delete" {
            if (-not $Username) {
                Write-Host "Error: Se requiere -Username para eliminar usuario." -ForegroundColor Red
                exit 1
            }
            Remove-User -Username $Username
        }
        "addtogroup" {
            if (-not $Username -or -not $Group) {
                Write-Host "Error: Se requieren -Username y -Group para agregar usuario a grupo." -ForegroundColor Red
                exit 1
            }
            Add-UserToGroup -Username $Username -GroupName $Group
        }
        default {
            Write-Host "Acción no válida. Use: create, list, update, delete, addtogroup" -ForegroundColor Red
        }
    }
}
